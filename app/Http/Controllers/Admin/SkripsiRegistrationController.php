<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiRegistrationRequest;
use App\Http\Requests\StoreSkripsiRegistrationRequest;
use App\Http\Requests\UpdateSkripsiRegistrationRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationAssignment;
use App\Models\Dosen;
use App\Models\Keilmuan;
use App\Models\SkripsiRegistration;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SkripsiRegistrationController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('skripsi_registration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SkripsiRegistration::with(['application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by'])->select(sprintf('%s.*', (new SkripsiRegistration)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'skripsi_registration_show';
                $editGate      = 'skripsi_registration_edit';
                $deleteGate    = 'skripsi_registration_delete';
                $crudRoutePart = 'skripsi-registrations';

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

            $table->addColumn('theme_name', function ($row) {
                return $row->theme ? $row->theme->name : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('abstract', function ($row) {
                return $row->abstract ? $row->abstract : '';
            });
            $table->addColumn('tps_lecturer_nama', function ($row) {
                return $row->tps_lecturer ? $row->tps_lecturer->nama : '';
            });

            $table->addColumn('preference_supervision_nama', function ($row) {
                return $row->preference_supervision ? $row->preference_supervision->nama : '';
            });

            $table->editColumn('khs_all', function ($row) {
                if (! $row->khs_all) {
                    return '';
                }
                $links = [];
                foreach ($row->khs_all as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('krs_latest', function ($row) {
                return $row->krs_latest ? '<a href="' . $row->krs_latest->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'theme', 'tps_lecturer', 'preference_supervision', 'khs_all', 'krs_latest']);

            return $table->make(true);
        }

        return view('admin.skripsiRegistrations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_registration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.skripsiRegistrations.create', compact('applications', 'preference_supervisions', 'themes', 'tps_lecturers'));
    }

    public function store(StoreSkripsiRegistrationRequest $request)
    {
        $skripsiRegistration = SkripsiRegistration::create($request->all());

        foreach ($request->input('khs_all', []) as $file) {
            $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
        }

        if ($request->input('krs_latest', false)) {
            $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiRegistration->id]);
        }

        return redirect()->route('admin.skripsi-registrations.index');
    }

    public function edit(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiRegistration->load('application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by');

        return view('admin.skripsiRegistrations.edit', compact('applications', 'preference_supervisions', 'skripsiRegistration', 'themes', 'tps_lecturers'));
    }

    public function update(UpdateSkripsiRegistrationRequest $request, SkripsiRegistration $skripsiRegistration)
    {
        $skripsiRegistration->update($request->all());

        if (count($skripsiRegistration->khs_all) > 0) {
            foreach ($skripsiRegistration->khs_all as $media) {
                if (! in_array($media->file_name, $request->input('khs_all', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiRegistration->khs_all->pluck('file_name')->toArray();
        foreach ($request->input('khs_all', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
            }
        }

        if ($request->input('krs_latest', false)) {
            if (! $skripsiRegistration->krs_latest || $request->input('krs_latest') !== $skripsiRegistration->krs_latest->file_name) {
                if ($skripsiRegistration->krs_latest) {
                    $skripsiRegistration->krs_latest->delete();
                }
                $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
            }
        } elseif ($skripsiRegistration->krs_latest) {
            $skripsiRegistration->krs_latest->delete();
        }

        return redirect()->route('admin.skripsi-registrations.index');
    }

    public function show(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiRegistration->load('application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by');

        return view('admin.skripsiRegistrations.show', compact('skripsiRegistration'));
    }

    public function destroy(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiRegistration->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiRegistrationRequest $request)
    {
        $skripsiRegistrations = SkripsiRegistration::find(request('ids'));

        foreach ($skripsiRegistrations as $skripsiRegistration) {
            $skripsiRegistration->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_registration_create') && Gate::denies('skripsi_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiRegistration();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    /**
     * Approve registration and assign supervisor
     */
    public function approve(Request $request, $id)
    {
        $registration = SkripsiRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'supervisor_id' => 'required|exists:dosens,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'assigned_supervisor_id' => $request->supervisor_id,
                    'approval_date' => now(),
                ]);

                // Update application status to approved (admin approved, waiting for supervisor acceptance)
                $registration->application->update([
                    'status' => 'approved',
                ]);

                // Create ApplicationAssignment for supervisor
                ApplicationAssignment::create([
                    'application_id' => $registration->application_id,
                    'lecturer_id' => $request->supervisor_id,
                    'role' => 'supervisor',
                    'status' => 'assigned', // Waiting for supervisor to accept/reject
                    'assigned_at' => now(),
                    'note' => $request->notes,
                ]);

                // Log action
                ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => ApplicationAction::ACTION_APPROVED,
                    'action_by' => auth()->id(),
                    'notes' => $request->notes,
                    'metadata' => [
                        'assigned_supervisor_id' => $request->supervisor_id,
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil disetujui dan dosen pembimbing telah ditugaskan. Menunggu persetujuan dari dosen pembimbing.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject registration with reason
     */
    public function reject(Request $request, $id)
    {
        $registration = SkripsiRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'rejection_reason' => $request->reason,
                ]);

                // Update application status
                $registration->application->update([
                    'status' => 'rejected',
                ]);

                // Log action
                ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => ApplicationAction::ACTION_REJECTED,
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran ditolak'
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
        $registration = SkripsiRegistration::with('application')->findOrFail($id);
        
        $request->validate([
            'notes' => 'required|string|min:10',
        ]);

        try {
            DB::transaction(function () use ($registration, $request) {
                // Update registration
                $registration->update([
                    'revision_notes' => $request->notes,
                ]);

                // Update application status
                $registration->application->update([
                    'status' => 'revision',
                ]);

                // Log action
                ApplicationAction::create([
                    'application_id' => $registration->application_id,
                    'action_type' => ApplicationAction::ACTION_REVISION_REQUESTED,
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
