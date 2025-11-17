<?php

namespace App\Http\Controllers\Frontend;

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

class ApplicationReportController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get current mahasiswa's application reports
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            $applicationReports = collect();
        } else {
            $applicationIds = Application::where('mahasiswa_id', $mahasiswa->id)->pluck('id');
            $applicationReports = ApplicationReport::with(['application', 'media'])
                ->whereIn('application_id', $applicationIds)
                ->get();
        }

        return view('frontend.applicationReports.index', compact('applicationReports'));
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

        return view('frontend.applicationReports.create', compact('activeApplication'));
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

        return redirect()->route('frontend.application-reports.index');
    }

    public function edit(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationReport->load('application');

        return view('frontend.applicationReports.edit', compact('applicationReport'));
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

        return redirect()->route('frontend.application-reports.index');
    }

    public function show(ApplicationReport $applicationReport)
    {
        abort_if(Gate::denies('application_report_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationReport->load('application');

        return view('frontend.applicationReports.show', compact('applicationReport'));
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
