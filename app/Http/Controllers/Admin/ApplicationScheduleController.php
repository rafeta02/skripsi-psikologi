<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationScheduleRequest;
use App\Http\Requests\StoreApplicationScheduleRequest;
use App\Http\Requests\UpdateApplicationScheduleRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationSchedule;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationScheduleController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('application_schedule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationSchedule::with(['application.mahasiswa', 'ruang'])->select(sprintf('%s.*', (new ApplicationSchedule)->table));
            
            // Filter by status if provided
            if ($request->has('status_filter')) {
                $statusFilter = $request->get('status_filter');
                
                if ($statusFilter === 'pending') {
                    $query->whereHas('application', function($q) {
                        $q->whereIn('status', ['submitted', 'approved']);
                    });
                } elseif ($statusFilter === 'approved') {
                    $query->whereHas('application', function($q) {
                        $q->where('status', 'scheduled');
                    });
                } elseif ($statusFilter === 'rejected') {
                    $query->whereHas('application', function($q) {
                        $q->where('status', 'rejected');
                    });
                }
            }
            
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id;
            });

            $table->addColumn('status', function ($row) {
                return $row->application ? $row->application->status : '';
            });

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_schedule_show';
                $editGate      = 'application_schedule_edit';
                $deleteGate    = 'application_schedule_delete';
                $crudRoutePart = 'application-schedules';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('mahasiswa_name', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nama : '';
            });

            $table->addColumn('mahasiswa_nim', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nim : '';
            });

            $table->editColumn('schedule_type', function ($row) {
                return $row->schedule_type ? ApplicationSchedule::SCHEDULE_TYPE_SELECT[$row->schedule_type] : '';
            });

            $table->editColumn('waktu', function ($row) {
                return $row->waktu ? $row->waktu : '';
            });

            $table->addColumn('ruang_name', function ($row) {
                return $row->ruang ? $row->ruang->name : ($row->custom_place ?: 'Online');
            });

            $table->addColumn('status_badge', function ($row) {
                if (!$row->application) return '';
                $status = $row->application->status;
                $badges = [
                    'submitted' => '<span class="badge badge-info">Menunggu</span>',
                    'approved' => '<span class="badge badge-warning">Belum Dijadwalkan</span>',
                    'scheduled' => '<span class="badge badge-success">Disetujui</span>',
                    'rejected' => '<span class="badge badge-danger">Ditolak</span>',
                ];
                return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
            });

            $table->addColumn('rejection_reason', function ($row) {
                if (!$row->application || $row->application->status !== 'rejected') return '-';
                $notes = $row->application->notes;
                return $notes ? (strlen($notes) > 50 ? substr($notes, 0, 50) . '...' : $notes) : '-';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '';
            });

            $table->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d M Y H:i') : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'ruang', 'status_badge']);

            return $table->make(true);
        }

        return view('admin.applicationSchedules.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_schedule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationSchedules.create', compact('applications', 'ruangs'));
    }

    public function store(StoreApplicationScheduleRequest $request)
    {
        $applicationSchedule = ApplicationSchedule::create($request->all());

        foreach ($request->input('approval_form', []) as $file) {
            $applicationSchedule->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('approval_form');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationSchedule->id]);
        }

        return redirect()->route('admin.application-schedules.index');
    }

    public function edit(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationSchedule->load('application', 'ruang');

        return view('admin.applicationSchedules.edit', compact('applicationSchedule', 'applications', 'ruangs'));
    }

    public function update(UpdateApplicationScheduleRequest $request, ApplicationSchedule $applicationSchedule)
    {
        $applicationSchedule->update($request->all());

        if (count($applicationSchedule->approval_form) > 0) {
            foreach ($applicationSchedule->approval_form as $media) {
                if (! in_array($media->file_name, $request->input('approval_form', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationSchedule->approval_form->pluck('file_name')->toArray();
        foreach ($request->input('approval_form', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationSchedule->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('approval_form');
            }
        }

        return redirect()->route('admin.application-schedules.index');
    }

    public function show(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationSchedule->load('application.mahasiswa.prodi', 'application.mahasiswa.jenjang', 'ruang', 'application.actions');

        return view('admin.applicationSchedules.show', compact('applicationSchedule'));
    }

    public function destroy(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationSchedule->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationScheduleRequest $request)
    {
        $applicationSchedules = ApplicationSchedule::find(request('ids'));

        foreach ($applicationSchedules as $applicationSchedule) {
            $applicationSchedule->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_schedule_create') && Gate::denies('application_schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationSchedule();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    /**
     * Approve schedule
     */
    public function approve(Request $request, $id)
    {
        $schedule = ApplicationSchedule::with('application')->findOrFail($id);
        
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($schedule, $request) {
                // Update application status to scheduled
                $schedule->application->update([
                    'status' => 'scheduled',
                ]);

                // Log action
                ApplicationAction::create([
                    'application_id' => $schedule->application_id,
                    'action_type' => 'schedule_approved',
                    'action_by' => auth()->id(),
                    'notes' => $request->notes ?? 'Jadwal seminar disetujui',
                    'metadata' => [
                        'schedule_id' => $schedule->id,
                        'waktu' => $schedule->waktu,
                        'ruang_id' => $schedule->ruang_id,
                        'custom_place' => $schedule->custom_place,
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Jadwal seminar berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject schedule with reason
     */
    public function reject(Request $request, $id)
    {
        $schedule = ApplicationSchedule::with('application')->findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            DB::transaction(function () use ($schedule, $request) {
                // Update application notes with rejection reason
                $schedule->application->update([
                    'notes' => $request->reason,
                ]);

                // Log action
                ApplicationAction::create([
                    'application_id' => $schedule->application_id,
                    'action_type' => 'schedule_rejected',
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Jadwal seminar ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
