<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationReportRequest;
use App\Http\Requests\StoreApplicationReportRequest;
use App\Http\Requests\UpdateApplicationReportRequest;
use App\Models\Application;
use App\Models\ApplicationReport;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationReportController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('application_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationReport::with(['application'])->select(sprintf('%s.*', (new ApplicationReport)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_report_show';
                $editGate      = 'application_report_edit';
                $deleteGate    = 'application_report_delete';
                $crudRoutePart = 'application-reports';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('application_type', function ($row) {
                return $row->application ? $row->application->type : '';
            });

            $table->editColumn('period', function ($row) {
                return $row->period ? $row->period : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? ApplicationReport::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application']);

            return $table->make(true);
        }

        return view('admin.applicationReports.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_report_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationReports.create', compact('applications'));
    }

    public function store(StoreApplicationReportRequest $request)
    {
        $applicationReport = ApplicationReport::create($request->all());

        foreach ($request->input('report_document', []) as $file) {
            $applicationReport->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationReport->id]);
        }

        return redirect()->route('admin.application-reports.index');
    }

    public function edit(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationReport->load('application');

        return view('admin.applicationReports.edit', compact('applicationReport', 'applications'));
    }

    public function update(UpdateApplicationReportRequest $request, ApplicationReport $applicationReport)
    {
        $applicationReport->update($request->all());

        if (count($applicationReport->report_document) > 0) {
            foreach ($applicationReport->report_document as $media) {
                if (! in_array($media->file_name, $request->input('report_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationReport->report_document->pluck('file_name')->toArray();
        foreach ($request->input('report_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationReport->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
            }
        }

        return redirect()->route('admin.application-reports.index');
    }

    public function show(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationReport->load('application');

        return view('admin.applicationReports.show', compact('applicationReport'));
    }

    public function destroy(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationReport->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationReportRequest $request)
    {
        $applicationReports = ApplicationReport::find(request('ids'));

        foreach ($applicationReports as $applicationReport) {
            $applicationReport->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_report_create') && Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationReport();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
