<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultSeminarRequest;
use App\Http\Requests\StoreApplicationResultSeminarRequest;
use App\Http\Requests\UpdateApplicationResultSeminarRequest;
use App\Models\Application;
use App\Models\ApplicationResultSeminar;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationResultSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('application_result_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationResultSeminar::with(['application'])->select(sprintf('%s.*', (new ApplicationResultSeminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_result_seminar_show';
                $editGate      = 'application_result_seminar_edit';
                $deleteGate    = 'application_result_seminar_delete';
                $crudRoutePart = 'application-result-seminars';

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

            $table->editColumn('result', function ($row) {
                return $row->result ? ApplicationResultSeminar::RESULT_SELECT[$row->result] : '';
            });

            $table->editColumn('report_document', function ($row) {
                if (! $row->report_document) {
                    return '';
                }
                $links = [];
                foreach ($row->report_document as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('latest_script', function ($row) {
                return $row->latest_script ? '<a href="' . $row->latest_script->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'report_document', 'latest_script']);

            return $table->make(true);
        }

        return view('admin.applicationResultSeminars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationResultSeminars.create', compact('applications'));
    }

    public function store(StoreApplicationResultSeminarRequest $request)
    {
        $applicationResultSeminar = ApplicationResultSeminar::create($request->all());

        foreach ($request->input('report_document', []) as $file) {
            $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
        }

        if ($request->input('attendance_document', false)) {
            $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('attendance_document'))))->toMediaCollection('attendance_document');
        }

        foreach ($request->input('form_document', []) as $file) {
            $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('form_document');
        }

        if ($request->input('latest_script', false)) {
            $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
        }

        foreach ($request->input('documentation', []) as $file) {
            $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('documentation');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationResultSeminar->id]);
        }

        return redirect()->route('admin.application-result-seminars.index');
    }

    public function edit(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationResultSeminar->load('application');

        return view('admin.applicationResultSeminars.edit', compact('applicationResultSeminar', 'applications'));
    }

    public function update(UpdateApplicationResultSeminarRequest $request, ApplicationResultSeminar $applicationResultSeminar)
    {
        $applicationResultSeminar->update($request->all());

        if (count($applicationResultSeminar->report_document) > 0) {
            foreach ($applicationResultSeminar->report_document as $media) {
                if (! in_array($media->file_name, $request->input('report_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultSeminar->report_document->pluck('file_name')->toArray();
        foreach ($request->input('report_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
            }
        }

        if ($request->input('attendance_document', false)) {
            if (! $applicationResultSeminar->attendance_document || $request->input('attendance_document') !== $applicationResultSeminar->attendance_document->file_name) {
                if ($applicationResultSeminar->attendance_document) {
                    $applicationResultSeminar->attendance_document->delete();
                }
                $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('attendance_document'))))->toMediaCollection('attendance_document');
            }
        } elseif ($applicationResultSeminar->attendance_document) {
            $applicationResultSeminar->attendance_document->delete();
        }

        if (count($applicationResultSeminar->form_document) > 0) {
            foreach ($applicationResultSeminar->form_document as $media) {
                if (! in_array($media->file_name, $request->input('form_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultSeminar->form_document->pluck('file_name')->toArray();
        foreach ($request->input('form_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('form_document');
            }
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultSeminar->latest_script || $request->input('latest_script') !== $applicationResultSeminar->latest_script->file_name) {
                if ($applicationResultSeminar->latest_script) {
                    $applicationResultSeminar->latest_script->delete();
                }
                $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
            }
        } elseif ($applicationResultSeminar->latest_script) {
            $applicationResultSeminar->latest_script->delete();
        }

        if (count($applicationResultSeminar->documentation) > 0) {
            foreach ($applicationResultSeminar->documentation as $media) {
                if (! in_array($media->file_name, $request->input('documentation', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultSeminar->documentation->pluck('file_name')->toArray();
        foreach ($request->input('documentation', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultSeminar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('documentation');
            }
        }

        return redirect()->route('admin.application-result-seminars.index');
    }

    public function show(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultSeminar->load('application');

        return view('admin.applicationResultSeminars.show', compact('applicationResultSeminar'));
    }

    public function destroy(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationResultSeminarRequest $request)
    {
        $applicationResultSeminars = ApplicationResultSeminar::find(request('ids'));

        foreach ($applicationResultSeminars as $applicationResultSeminar) {
            $applicationResultSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_result_seminar_create') && Gate::denies('application_result_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationResultSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
