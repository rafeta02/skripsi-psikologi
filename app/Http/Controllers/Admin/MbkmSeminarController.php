<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmSeminarRequest;
use App\Http\Requests\StoreMbkmSeminarRequest;
use App\Http\Requests\UpdateMbkmSeminarRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationAssignment;
use App\Models\MbkmRegistration;
use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MbkmSeminarController extends Controller
{
    use MediaUploadingTrait;

    /** @var array<int, MbkmRegistration|null> */
    private array $registrationCache = [];

    public function index(Request $request)
    {
        abort_if(Gate::denies('mbkm_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MbkmSeminar::with([
                'application.mahasiswa',
                'application.mbkmRegistration.groupMembers.mahasiswa',
                'application.mbkmRegistration.preference_supervision',
                'application.parentApplication.mbkmRegistration.groupMembers.mahasiswa',
                'application.parentApplication.mbkmRegistration.preference_supervision',
                'created_by',
            ])->select(sprintf('%s.*', (new MbkmSeminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mbkm_seminar_show';
                $editGate      = 'mbkm_seminar_edit';
                $deleteGate    = 'mbkm_seminar_delete';
                $crudRoutePart = 'mbkm-seminars';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('kelompok', function ($row) {
                return $this->formatKelompokHtml($row);
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? e($row->title) : '<span class="text-muted">-</span>';
            });

            $table->addColumn('pembimbing', function ($row) {
                return e($this->resolvePembimbingNama($row) ?? '-');
            });

            $table->rawColumns(['actions', 'placeholder', 'kelompok', 'title']);

            return $table->make(true);
        }

        return view('admin.mbkmSeminars.index');
    }

    private function formatKelompokHtml(MbkmSeminar $seminar): string
    {
        $registration = $this->resolveMbkmRegistration($seminar);
        $members = $registration?->groupMembers;

        if (!$members || $members->isEmpty()) {
            $ketua = $seminar->application->mahasiswa ?? null;
            if (!$ketua) {
                return '<span class="text-muted">-</span>';
            }

            return '<div><span class="badge badge-success mr-1">Ketua</span>'
                . e($ketua->nama)
                . ' <small class="text-muted">(' . e($ketua->nim ?? '-') . ')</small></div>';
        }

        $sorted = $members->sortBy(fn ($m) => $m->role === 'ketua' ? 0 : 1);
        $html = '<ul class="list-unstyled mb-0 small">';
        foreach ($sorted as $member) {
            $roleBadge = $member->role === 'ketua'
                ? '<span class="badge badge-success mr-1">Ketua</span>'
                : '<span class="badge badge-secondary mr-1">Anggota</span>';
            $nama = e($member->mahasiswa->nama ?? '-');
            $nim = e($member->mahasiswa->nim ?? '-');
            $html .= '<li class="mb-1">' . $roleBadge . $nama
                . ' <small class="text-muted">(' . $nim . ')</small></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function resolvePembimbingNama(MbkmSeminar $seminar): ?string
    {
        $registration = $this->resolveMbkmRegistration($seminar);
        if (!$registration) {
            return null;
        }

        $registrationAppId = $registration->application_id;
        if ($registrationAppId) {
            $assignment = ApplicationAssignment::with('lecturer')
                ->where('application_id', $registrationAppId)
                ->where('role', 'supervisor')
                ->whereIn('status', ['accepted', 'assigned'])
                ->orderByRaw("CASE status WHEN 'accepted' THEN 0 ELSE 1 END")
                ->latest('id')
                ->first();

            if ($assignment?->lecturer?->nama) {
                return $assignment->lecturer->nama;
            }
        }

        return $registration->preference_supervision?->nama;
    }

    private function resolveMbkmRegistration(MbkmSeminar $seminar): ?MbkmRegistration
    {
        $application = $seminar->application;
        if (!$application) {
            return null;
        }

        $ownerMahasiswaId = (int) (
            ($application->is_group_mirror && $application->parentApplication)
                ? $application->parentApplication->mahasiswa_id
                : $application->mahasiswa_id
        );

        if (!$ownerMahasiswaId) {
            return null;
        }

        if (array_key_exists($ownerMahasiswaId, $this->registrationCache)) {
            return $this->registrationCache[$ownerMahasiswaId];
        }

        $registration = $application->resolveOwnerMbkmRegistration();

        if (!$registration) {
            $registration = MbkmRegistration::with(['groupMembers.mahasiswa', 'preference_supervision'])
                ->whereHas('application', function ($q) use ($ownerMahasiswaId) {
                    $q->where('mahasiswa_id', $ownerMahasiswaId)
                        ->where('type', 'mbkm')
                        ->where('stage', 'registration')
                        ->where(function ($inner) {
                            $inner->where('is_group_mirror', false)
                                ->orWhereNull('is_group_mirror');
                        });
                })
                ->latest('id')
                ->first();
        } else {
            $registration->loadMissing(['groupMembers.mahasiswa', 'preference_supervision']);
        }

        return $this->registrationCache[$ownerMahasiswaId] = $registration;
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mbkmSeminars.create', compact('applications'));
    }

    public function store(StoreMbkmSeminarRequest $request)
    {
        $mbkmSeminar = MbkmSeminar::create($request->all());

        if ($request->input('proposal_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
        }

        if ($request->input('approval_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
        }

        if ($request->input('plagiarism_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mbkmSeminar->id]);
        }

        return redirect()->route('admin.mbkm-seminars.index');
    }

    public function edit(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmSeminar->load('application', 'created_by');

        return view('admin.mbkmSeminars.edit', compact('applications', 'mbkmSeminar'));
    }

    public function update(UpdateMbkmSeminarRequest $request, MbkmSeminar $mbkmSeminar)
    {
        $mbkmSeminar->update($request->all());

        if ($request->input('proposal_document', false)) {
            if (! $mbkmSeminar->proposal_document || $request->input('proposal_document') !== $mbkmSeminar->proposal_document->file_name) {
                if ($mbkmSeminar->proposal_document) {
                    $mbkmSeminar->proposal_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
            }
        } elseif ($mbkmSeminar->proposal_document) {
            $mbkmSeminar->proposal_document->delete();
        }

        if ($request->input('approval_document', false)) {
            if (! $mbkmSeminar->approval_document || $request->input('approval_document') !== $mbkmSeminar->approval_document->file_name) {
                if ($mbkmSeminar->approval_document) {
                    $mbkmSeminar->approval_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
            }
        } elseif ($mbkmSeminar->approval_document) {
            $mbkmSeminar->approval_document->delete();
        }

        if ($request->input('plagiarism_document', false)) {
            if (! $mbkmSeminar->plagiarism_document || $request->input('plagiarism_document') !== $mbkmSeminar->plagiarism_document->file_name) {
                if ($mbkmSeminar->plagiarism_document) {
                    $mbkmSeminar->plagiarism_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
            }
        } elseif ($mbkmSeminar->plagiarism_document) {
            $mbkmSeminar->plagiarism_document->delete();
        }

        return redirect()->route('admin.mbkm-seminars.index');
    }

    public function show(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->load([
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'application.mbkmRegistration.groupMembers.mahasiswa',
            'application.parentApplication.mbkmRegistration.groupMembers.mahasiswa',
            'application.actions',
            'created_by',
            'reviewer1',
            'reviewer2',
        ]);

        $mbkmGroupRegistration = $mbkmSeminar->application
            ? $mbkmSeminar->application->resolveOwnerMbkmRegistration()
            : null;

        if ($mbkmGroupRegistration && !$mbkmGroupRegistration->relationLoaded('groupMembers')) {
            $mbkmGroupRegistration->load('groupMembers.mahasiswa');
        }

        return view('admin.mbkmSeminars.show', compact('mbkmSeminar', 'mbkmGroupRegistration'));
    }

    public function destroy(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmSeminarRequest $request)
    {
        $mbkmSeminars = MbkmSeminar::find(request('ids'));

        foreach ($mbkmSeminars as $mbkmSeminar) {
            $mbkmSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mbkm_seminar_create') && Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MbkmSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function approve(Request $request, MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'reviewer_1_id' => 'required|exists:dosens,id',
            'reviewer_2_id' => 'required|exists:dosens,id|different:reviewer_1_id',
            'notes' => 'nullable|string',
        ]);

        $seminar = MbkmSeminar::with('application')->findOrFail($mbkmSeminar->id);

        if (!$seminar->application) {
            return response()->json([
                'success' => false,
                'message' => 'Aplikasi tidak ditemukan',
            ], 404);
        }

        try {
            DB::transaction(function () use ($seminar, $request) {
                $seminar->update([
                    'reviewer_1_id' => $request->reviewer_1_id,
                    'reviewer_2_id' => $request->reviewer_2_id,
                ]);

                $seminar->application->update([
                    'status' => 'approved',
                ]);

                ApplicationAction::create([
                    'application_id' => $seminar->application_id,
                    'action_type' => 'seminar_approved',
                    'action_by' => auth()->id(),
                    'notes' => $request->notes ?? 'Review Kelayakan Proposal (MBKM) disetujui',
                    'metadata' => [
                        'reviewer_1_id' => $request->reviewer_1_id,
                        'reviewer_2_id' => $request->reviewer_2_id,
                        'seminar_type' => 'mbkm',
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Review Kelayakan Proposal (MBKM) berhasil disetujui dan reviewer telah ditugaskan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            $application = $mbkmSeminar->application;
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aplikasi tidak ditemukan'
                ], 404);
            }

            DB::transaction(function () use ($application, $request, $mbkmSeminar) {
                $application->update([
                    'status' => 'rejected',
                    'notes' => $request->reason,
                ]);

                ApplicationAction::create([
                    'application_id' => $application->id,
                    'action_type' => 'seminar_rejected',
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                    'metadata' => [
                        'mbkm_seminar_id' => $mbkmSeminar->id,
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Review Kelayakan Proposal (MBKM) berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
