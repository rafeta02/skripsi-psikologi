<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmRegistrationRequest;
use App\Http\Requests\StoreMbkmRegistrationRequest;
use App\Http\Requests\UpdateMbkmRegistrationRequest;
use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\Dosen;
use App\Models\Keilmuan;
use App\Models\MbkmRegistration;
use App\Models\ResearchGroup;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MbkmRegistrationController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mbkm_registration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MbkmRegistration::with([
                'application.mahasiswa',
                'research_group',
                'preference_supervision',
                'theme',
                'themes',
                'created_by',
                'groupMembers.mahasiswa',
            ])->select(sprintf('%s.*', (new MbkmRegistration)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mbkm_registration_show';
                $editGate      = 'mbkm_registration_admin_edit_disabled';
                $deleteGate    = 'mbkm_registration_delete';
                $crudRoutePart = 'mbkm-registrations';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('research_group_name', function ($row) {
                return $row->research_group ? $row->research_group->name : '';
            });

            $table->addColumn('preference_supervision_nip', function ($row) {
                return $row->preference_supervision ? $row->preference_supervision->nama : '';
            });

            $table->addColumn('theme_name', function ($row) {
                return $row->themes_label ?: ($row->theme->name ?? '');
            });

            $table->editColumn('title_mbkm', function ($row) {
                return $row->title_mbkm ? $row->title_mbkm : '';
            });
            $table->addColumn('group_status_label', function ($row) {
                $status = $row->group_status ?? 'draft';
                if ($status === 'submitted') {
                    return '<span class="badge badge-success">Diajukan</span>';
                }

                return '<span class="badge badge-warning">Draft</span>';
            });
            $table->addColumn('members_count', function ($row) {
                $total = $row->groupMembers ? $row->groupMembers->count() : 0;
                $complete = $row->groupMembers
                    ? $row->groupMembers->where('requirements_status', 'complete')->count()
                    : 0;

                return $total > 0 ? "{$complete}/{$total}" : '-';
            });
            $table->addColumn('kelompok_anggota', function ($row) {
                $members = $row->groupMembers;
                if (!$members || $members->isEmpty()) {
                    $ketua = $row->application->mahasiswa ?? null;
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
            });

            $table->rawColumns([
                'actions',
                'placeholder',
                'research_group',
                'preference_supervision',
                'theme',
                'group_status_label',
                'kelompok_anggota',
            ]);

            return $table->make(true);
        }

        return view('admin.mbkmRegistrations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_registration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $research_groups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mbkmRegistrations.create', compact('applications', 'preference_supervisions', 'research_groups', 'themes'));
    }

    public function store(StoreMbkmRegistrationRequest $request)
    {
        $mbkmRegistration = MbkmRegistration::create($request->all());

        foreach ($request->input('khs_all', []) as $index => $file) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'khs_all',
                $index + 1
            );
        }

        if ($request->input('krs_latest', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('krs_latest'))),
                'krs_latest'
            );
        }

        if ($request->input('spp', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('spp'))),
                'spp'
            );
        }

        if ($request->input('proposal_mbkm', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('proposal_mbkm'))),
                'proposal_mbkm'
            );
        }

        if ($request->input('recognition_form', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('recognition_form'))),
                'recognition_form'
            );
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mbkmRegistration->id]);
        }

        return redirect()->route('admin.mbkm-registrations.index');
    }

    public function edit(MbkmRegistration $mbkmRegistration)
    {
        abort(Response::HTTP_FORBIDDEN, 'Admin tidak dapat mengedit form mahasiswa.');
    }

    public function update(UpdateMbkmRegistrationRequest $request, MbkmRegistration $mbkmRegistration)
    {
        abort(Response::HTTP_FORBIDDEN, 'Admin tidak dapat mengedit form mahasiswa.');
    }

    public function show(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistration->load([
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'application.actions.actionBy',
            'research_group',
            'preference_supervision',
            'themes',
            'theme',
            'created_by',
            'groupMembers.mahasiswa',
            'groupMembers.media',
        ]);

        return view('admin.mbkmRegistrations.show', compact('mbkmRegistration'));
    }

    public function destroy(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistration->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmRegistrationRequest $request)
    {
        $mbkmRegistrations = MbkmRegistration::find(request('ids'));

        foreach ($mbkmRegistrations as $mbkmRegistration) {
            $mbkmRegistration->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mbkm_registration_create') && Gate::denies('mbkm_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MbkmRegistration();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    /**
     * Approve MBKM registration
     */
    public function approve(Request $request, $id)
    {
        $registration = MbkmRegistration::with(['application', 'groupMembers'])->findOrFail($id);

        if (($registration->group_status ?? 'draft') !== 'submitted') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan kelompok belum di-submit oleh ketua (masih draft / menunggu syarat individu).',
            ], 422);
        }

        if ($registration->approval_date) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran ini sudah disetujui admin sebelumnya.',
            ], 422);
        }

        if (ApplicationAssignment::where('application_id', $registration->application_id)
            ->where('role', 'supervisor')
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran ini sudah diteruskan ke dosen pembimbing.',
            ], 422);
        }

        if (!$registration->allMembersRequirementsComplete()) {
            return response()->json([
                'success' => false,
                'message' => 'Belum semua anggota kelompok melengkapi syarat individu.',
            ], 422);
        }
        
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            \DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'approval_date' => now(),
                ]);

                // Keep submitted until supervisor accepts the assignment
                if ($registration->application->status === 'revision') {
                    $registration->application->update(['status' => 'submitted']);
                }

                // Create ApplicationAssignment for the preferred supervisor (owner/ketua only)
                if ($registration->preference_supervision_id) {
                    app(\App\Services\MbkmGroupProgressService::class)->purgeMirrorAssignments();

                    ApplicationAssignment::create([
                        'application_id' => $registration->application_id,
                        'lecturer_id' => $registration->preference_supervision_id,
                        'role' => 'supervisor',
                        'status' => 'assigned', // Waiting for supervisor to accept/reject
                        'assigned_at' => now(),
                        'note' => $request->notes,
                    ]);
                }

                // Log action
                \App\Models\ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => \App\Models\ApplicationAction::ACTION_APPROVED,
                    'action_by' => auth()->id(),
                    'notes' => $request->notes,
                    'metadata' => [
                        'supervisor_id' => $registration->preference_supervision_id,
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran MBKM berhasil disetujui dan dosen pembimbing telah ditugaskan. Menunggu persetujuan dari dosen pembimbing.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject MBKM registration with reason
     */
    public function reject(Request $request, $id)
    {
        $registration = MbkmRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            \DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'rejection_reason' => $request->reason,
                ]);

                // Update application status
                $registration->application->update([
                    'status' => 'rejected',
                ]);

                // Log action
                \App\Models\ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => \App\Models\ApplicationAction::ACTION_REJECTED,
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran MBKM ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request revision with notes
     */
    public function requestRevision(Request $request, $id)
    {
        $registration = MbkmRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'notes' => 'required|string|min:10',
        ]);

        try {
            \DB::transaction(function () use ($registration, $request) {
                app(\App\Services\MbkmGroupProgressService::class)
                    ->reopenGroupForRevision($registration, $request->notes);

                \App\Models\ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => \App\Models\ApplicationAction::ACTION_REVISION_REQUESTED,
                    'action_by' => auth()->id(),
                    'notes' => $request->notes,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Revisi diminta. Form kelompok dibuka kembali agar mahasiswa dapat mengedit dan submit ulang.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
