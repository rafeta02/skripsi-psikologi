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
            $query = ApplicationReport::with(['application.mahasiswa'])->select(sprintf('%s.*', (new ApplicationReport)->table));
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

            $table->addColumn('mahasiswa_name', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nama : 'N/A';
            });

            $table->addColumn('mahasiswa_nim', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nim : 'N/A';
            });

            $table->addColumn('application_type', function ($row) {
                return $row->application ? $row->application->type : '';
            });

            $table->editColumn('period', function ($row) {
                return $row->period ? $row->period : '-';
            });
            
            $table->editColumn('status', function ($row) {
                $statusBadge = '';
                if ($row->status == 'submitted') {
                    $statusBadge = '<span class="badge badge-warning">Submitted</span>';
                } elseif ($row->status == 'reviewed') {
                    $statusBadge = '<span class="badge badge-success">Reviewed</span>';
                }
                return $statusBadge;
            });

            $table->addColumn('review_action', function ($row) {
                if ($row->status == 'submitted') {
                    return '<button type="button" class="btn btn-sm btn-primary btn-review" 
                            data-id="'.$row->id.'" 
                            data-mahasiswa="'.($row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nama : 'N/A').'"
                            data-note="'.htmlspecialchars($row->note ?? '').'"
                            data-toggle="modal" 
                            data-target="#reviewModal">
                            <i class="fas fa-check"></i> Mark as Reviewed
                        </button>';
                } else {
                    return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Reviewed</span>';
                }
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? substr(strip_tags($row->note), 0, 50).'...' : '-';
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'review_action']);

            return $table->make(true);
        }

        return view('admin.applicationReports.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_report_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get current mahasiswa's active application
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan');
        }

        $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activeApplication) {
            return redirect()->back()->with('error', 'Tidak ada aplikasi aktif. Silakan buat aplikasi terlebih dahulu.');
        }

        return view('admin.applicationReports.create', compact('activeApplication'));
    }

    public function store(StoreApplicationReportRequest $request)
    {
        $data = $request->all();
        // Automatically set status to 'submitted'
        $data['status'] = 'submitted';
        
        $applicationReport = ApplicationReport::create($data);

        foreach ($request->input('report_document', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $applicationReport->addMedia($filePath)
                ->usingFileName($applicationReport->generateCustomFileName($filePath, 'report_document'))
                ->toMediaCollection('report_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationReport->id]);
        }

        return redirect()->route('admin.application-reports.index');
    }

    public function edit(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationReport->load('application');

        return view('admin.applicationReports.edit', compact('applicationReport'));
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $applicationReport->addMedia($filePath)
                    ->usingFileName($applicationReport->generateCustomFileName($filePath, 'report_document'))
                    ->toMediaCollection('report_document');
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

    public function markAsReviewed(Request $request, ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = ['status' => 'reviewed'];
        
        // Add note if provided
        if ($request->filled('note')) {
            $data['note'] = $request->input('note');
        }

        $applicationReport->update($data);

        return redirect()->route('admin.application-reports.index')
            ->with('message', 'Laporan telah ditandai sebagai Reviewed');
    }
}
