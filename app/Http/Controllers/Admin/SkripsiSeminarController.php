<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiSeminarRequest;
use App\Http\Requests\StoreSkripsiSeminarRequest;
use App\Http\Requests\UpdateSkripsiSeminarRequest;
use App\Models\Application;
use App\Models\SkripsiSeminar;
use App\Models\Dosen;
use App\Models\ApplicationAssignment;
use App\Services\ReviewerAssignmentService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SkripsiSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('skripsi_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SkripsiSeminar::with([
                'application.mahasiswa',
                'created_by',
            ])->select(sprintf('%s.*', (new SkripsiSeminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'skripsi_seminar_show';
                $editGate      = 'skripsi_seminar_admin_edit_disabled';
                $deleteGate    = 'skripsi_seminar_delete';
                $crudRoutePart = 'skripsi-seminars';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('mahasiswa_name', function ($row) {
                return $row->application && $row->application->mahasiswa
                    ? e($row->application->mahasiswa->nama)
                    : '<span class="text-muted">-</span>';
            });

            $table->addColumn('status_badge', function ($row) {
                return $row->adminStatusBadgeHtml();
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? e($row->title) : '<span class="text-muted">-</span>';
            });

            $table->editColumn('proposal_document', function ($row) {
                return $row->proposal_document
                    ? '<a href="' . $row->proposal_document->getUrl() . '" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-download"></i> Proposal</a>'
                    : '<span class="text-muted">-</span>';
            });

            $table->editColumn('approval_document', function ($row) {
                return $row->approval_document
                    ? '<a href="' . $row->approval_document->getUrl() . '" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-download"></i> Persetujuan</a>'
                    : '<span class="text-muted">-</span>';
            });

            $table->editColumn('plagiarism_document', function ($row) {
                return $row->plagiarism_document
                    ? '<a href="' . $row->plagiarism_document->getUrl() . '" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-download"></i> Plagiarisme</a>'
                    : '<span class="text-muted">-</span>';
            });

            $table->rawColumns([
                'actions',
                'placeholder',
                'mahasiswa_name',
                'status_badge',
                'title',
                'proposal_document',
                'approval_document',
                'plagiarism_document',
            ]);

            return $table->make(true);
        }

        return view('admin.skripsiSeminars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.skripsiSeminars.create', compact('applications'));
    }

    public function store(StoreSkripsiSeminarRequest $request)
    {
        $skripsiSeminar = SkripsiSeminar::create($request->all());

        if ($request->input('proposal_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
        }

        if ($request->input('approval_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
        }

        if ($request->input('plagiarism_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiSeminar->id]);
        }

        return redirect()->route('admin.skripsi-seminars.index');
    }

    public function edit(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiSeminar->load('application', 'created_by');

        return view('admin.skripsiSeminars.edit', compact('applications', 'skripsiSeminar'));
    }

    public function update(UpdateSkripsiSeminarRequest $request, SkripsiSeminar $skripsiSeminar)
    {
        $skripsiSeminar->update($request->all());

        if ($request->input('proposal_document', false)) {
            if (! $skripsiSeminar->proposal_document || $request->input('proposal_document') !== $skripsiSeminar->proposal_document->file_name) {
                if ($skripsiSeminar->proposal_document) {
                    $skripsiSeminar->proposal_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
            }
        } elseif ($skripsiSeminar->proposal_document) {
            $skripsiSeminar->proposal_document->delete();
        }

        if ($request->input('approval_document', false)) {
            if (! $skripsiSeminar->approval_document || $request->input('approval_document') !== $skripsiSeminar->approval_document->file_name) {
                if ($skripsiSeminar->approval_document) {
                    $skripsiSeminar->approval_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
            }
        } elseif ($skripsiSeminar->approval_document) {
            $skripsiSeminar->approval_document->delete();
        }

        if ($request->input('plagiarism_document', false)) {
            if (! $skripsiSeminar->plagiarism_document || $request->input('plagiarism_document') !== $skripsiSeminar->plagiarism_document->file_name) {
                if ($skripsiSeminar->plagiarism_document) {
                    $skripsiSeminar->plagiarism_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
            }
        } elseif ($skripsiSeminar->plagiarism_document) {
            $skripsiSeminar->plagiarism_document->delete();
        }

        return redirect()->route('admin.skripsi-seminars.index');
    }

    public function show(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->load(
            'application.mahasiswa',
            'application.skripsiRegistration',
            'created_by',
            'reviewer1',
            'reviewer2',
            'reviewerAssignments.lecturer'
        );

        $reviewerAssignments = $skripsiSeminar->reviewerAssignments()
            ->with('lecturer')
            ->orderBy('reviewer_slot')
            ->get();

        $supervisorId = $skripsiSeminar->application?->resolveSupervisorLecturerId();
        $supervisor = $supervisorId ? Dosen::find($supervisorId) : null;

        $dosens = Dosen::orderBy('nama')
            ->when($supervisorId, fn ($query) => $query->where('id', '!=', $supervisorId))
            ->get(['id', 'nama', 'nidn']);

        return view('admin.skripsiSeminars.show', compact(
            'skripsiSeminar',
            'reviewerAssignments',
            'dosens',
            'supervisor',
            'supervisorId'
        ));
    }

    public function destroy(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiSeminarRequest $request)
    {
        $skripsiSeminars = SkripsiSeminar::find(request('ids'));

        foreach ($skripsiSeminars as $skripsiSeminar) {
            $skripsiSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_seminar_create') && Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    /**
     * Approve seminar proposal and assign reviewers
     */
    public function approve(Request $request, $id)
    {
        $seminar = SkripsiSeminar::with('application')->findOrFail($id);
        
        $request->validate([
            'reviewer_1_id' => 'required|exists:dosens,id',
            'reviewer_2_id' => 'required|exists:dosens,id|different:reviewer_1_id',
            'notes' => 'nullable|string',
        ]);

        $supervisorId = $seminar->application?->resolveSupervisorLecturerId();
        if ($supervisorId && in_array($supervisorId, [(int) $request->reviewer_1_id, (int) $request->reviewer_2_id], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen pembimbing tidak dapat ditugaskan sebagai reviewer.',
            ], 422);
        }

        try {
            app(ReviewerAssignmentService::class)->assignReviewers(
                $seminar,
                (int) $request->reviewer_1_id,
                (int) $request->reviewer_2_id,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Review Kelayakan Proposal (Reguler) berhasil disetujui dan reviewer telah ditugaskan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reassign reviewer when expired/rejected.
     */
    public function reassignReviewer(Request $request, ApplicationAssignment $assignment)
    {
        abort_if(Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($assignment->role !== 'reviewer' || ! in_array($assignment->status, ['expired', 'rejected'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Penugasan reviewer tidak dapat diganti pada status ini.',
            ], 422);
        }

        $request->validate([
            'lecturer_id' => 'required|exists:dosens,id',
            'note' => 'nullable|string|max:1000',
        ]);

        if ((int) $request->lecturer_id === (int) $assignment->lecturer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih dosen reviewer yang berbeda dari penugasan sebelumnya.',
            ], 422);
        }

        $seminar = SkripsiSeminar::with('application')->find($assignment->skripsi_seminar_id);
        $supervisorId = $seminar?->application?->resolveSupervisorLecturerId();
        if ($supervisorId && (int) $request->lecturer_id === $supervisorId) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen pembimbing tidak dapat ditugaskan sebagai reviewer.',
            ], 422);
        }

        try {
            $newAssignment = app(ReviewerAssignmentService::class)->reassignReviewer(
                $assignment,
                (int) $request->lecturer_id,
                $request->note
            );

            return response()->json([
                'success' => true,
                'message' => 'Reviewer berhasil diganti.',
                'assignment_id' => $newAssignment->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject seminar proposal with reason
     */
    public function reject(Request $request, $id)
    {
        $seminar = SkripsiSeminar::with('application')->findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            DB::transaction(function () use ($seminar, $request) {
                // Update application status to rejected
                $seminar->application->update([
                    'status' => 'rejected',
                    'notes' => $request->reason,
                ]);

                // Log action
                \App\Models\ApplicationAction::create([
                    'application_id' => $seminar->application_id,
                    'action_type' => 'seminar_rejected',
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Review Kelayakan Proposal (Reguler) ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
