<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationScheduleRequest;
use App\Http\Requests\StoreApplicationScheduleRequest;
use App\Http\Requests\UpdateApplicationScheduleRequest;
use App\Models\Application;
use App\Models\ApplicationSchedule;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
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
            $query = ApplicationSchedule::with(['application', 'ruang'])->select(sprintf('%s.*', (new ApplicationSchedule)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

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

            $table->addColumn('application_status', function ($row) {
                return $row->application ? $row->application->status : '';
            });

            $table->editColumn('schedule_type', function ($row) {
                return $row->schedule_type ? ApplicationSchedule::SCHEDULE_TYPE_SELECT[$row->schedule_type] : '';
            });

            $table->addColumn('ruang_name', function ($row) {
                return $row->ruang ? $row->ruang->name : '';
            });

            $table->editColumn('online_meeting', function ($row) {
                return $row->online_meeting ? $row->online_meeting : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'ruang']);

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

        $applicationSchedule->load('application', 'ruang');

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
}
