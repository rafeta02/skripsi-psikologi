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
            $query = MbkmRegistration::with(['application', 'research_group', 'preference_supervision', 'theme', 'created_by'])->select(sprintf('%s.*', (new MbkmRegistration)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mbkm_registration_show';
                $editGate      = 'mbkm_registration_edit';
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

            $table->addColumn('application_status', function ($row) {
                return $row->application ? $row->application->status : '';
            });

            $table->addColumn('research_group_name', function ($row) {
                return $row->research_group ? $row->research_group->name : '';
            });

            $table->addColumn('preference_supervision_nip', function ($row) {
                return $row->preference_supervision ? $row->preference_supervision->nama : '';
            });

            $table->addColumn('theme_name', function ($row) {
                return $row->theme ? $row->theme->name : '';
            });

            $table->editColumn('title_mbkm', function ($row) {
                return $row->title_mbkm ? $row->title_mbkm : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('total_sks_taken', function ($row) {
                return $row->total_sks_taken ? $row->total_sks_taken : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'research_group', 'preference_supervision', 'theme']);

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

        foreach ($request->input('khs_all', []) as $file) {
            $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
        }

        if ($request->input('krs_latest', false)) {
            $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
        }

        if ($request->input('spp', false)) {
            $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('spp'))))->toMediaCollection('spp');
        }

        if ($request->input('proposal_mbkm', false)) {
            $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_mbkm'))))->toMediaCollection('proposal_mbkm');
        }

        if ($request->input('recognition_form', false)) {
            $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('recognition_form'))))->toMediaCollection('recognition_form');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mbkmRegistration->id]);
        }

        return redirect()->route('admin.mbkm-registrations.index');
    }

    public function edit(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $research_groups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmRegistration->load('application', 'research_group', 'preference_supervision', 'theme', 'created_by');

        return view('admin.mbkmRegistrations.edit', compact('applications', 'mbkmRegistration', 'preference_supervisions', 'research_groups', 'themes'));
    }

    public function update(UpdateMbkmRegistrationRequest $request, MbkmRegistration $mbkmRegistration)
    {
        $mbkmRegistration->update($request->all());

        if (count($mbkmRegistration->khs_all) > 0) {
            foreach ($mbkmRegistration->khs_all as $media) {
                if (! in_array($media->file_name, $request->input('khs_all', []))) {
                    $media->delete();
                }
            }
        }
        $media = $mbkmRegistration->khs_all->pluck('file_name')->toArray();
        foreach ($request->input('khs_all', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
            }
        }

        if ($request->input('krs_latest', false)) {
            if (! $mbkmRegistration->krs_latest || $request->input('krs_latest') !== $mbkmRegistration->krs_latest->file_name) {
                if ($mbkmRegistration->krs_latest) {
                    $mbkmRegistration->krs_latest->delete();
                }
                $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
            }
        } elseif ($mbkmRegistration->krs_latest) {
            $mbkmRegistration->krs_latest->delete();
        }

        if ($request->input('spp', false)) {
            if (! $mbkmRegistration->spp || $request->input('spp') !== $mbkmRegistration->spp->file_name) {
                if ($mbkmRegistration->spp) {
                    $mbkmRegistration->spp->delete();
                }
                $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('spp'))))->toMediaCollection('spp');
            }
        } elseif ($mbkmRegistration->spp) {
            $mbkmRegistration->spp->delete();
        }

        if ($request->input('proposal_mbkm', false)) {
            if (! $mbkmRegistration->proposal_mbkm || $request->input('proposal_mbkm') !== $mbkmRegistration->proposal_mbkm->file_name) {
                if ($mbkmRegistration->proposal_mbkm) {
                    $mbkmRegistration->proposal_mbkm->delete();
                }
                $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_mbkm'))))->toMediaCollection('proposal_mbkm');
            }
        } elseif ($mbkmRegistration->proposal_mbkm) {
            $mbkmRegistration->proposal_mbkm->delete();
        }

        if ($request->input('recognition_form', false)) {
            if (! $mbkmRegistration->recognition_form || $request->input('recognition_form') !== $mbkmRegistration->recognition_form->file_name) {
                if ($mbkmRegistration->recognition_form) {
                    $mbkmRegistration->recognition_form->delete();
                }
                $mbkmRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('recognition_form'))))->toMediaCollection('recognition_form');
            }
        } elseif ($mbkmRegistration->recognition_form) {
            $mbkmRegistration->recognition_form->delete();
        }

        return redirect()->route('admin.mbkm-registrations.index');
    }

    public function show(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistration->load('application', 'research_group', 'preference_supervision', 'theme', 'created_by');

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
        $registration = MbkmRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            \DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'approval_date' => now(),
                ]);

                // Update application status
                $registration->application->update([
                    'status' => 'approved',
                ]);

                // Create ApplicationAssignment for the preferred supervisor
                if ($registration->preference_supervision_id) {
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
                // Update registration
                $registration->update([
                    'revision_notes' => $request->notes,
                ]);

                // Update application status
                $registration->application->update([
                    'status' => 'revision',
                ]);

                // Log action
                \App\Models\ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => \App\Models\ApplicationAction::ACTION_REVISION_REQUESTED,
                    'action_by' => auth()->id(),
                    'notes' => $request->notes,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Revisi diminta, mahasiswa akan menerima notifikasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
