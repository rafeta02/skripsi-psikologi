<?php

namespace App\Http\Controllers\Frontend;

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

class ApplicationResultSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_result_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultSeminars = ApplicationResultSeminar::with(['application', 'media'])->get();

        return view('frontend.applicationResultSeminars.index', compact('applicationResultSeminars'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        return view('frontend.applicationResultSeminars.create', compact('activeApplication'));
    }

    public function store(StoreApplicationResultSeminarRequest $request)
    {
        $applicationResultSeminar = ApplicationResultSeminar::create($request->all());

        foreach ($request->input('report_document', []) as $file) {
            $applicationResultSeminar->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'report_document',
                'BA'
            );
        }

        if ($request->input('attendance_document', false)) {
            $applicationResultSeminar->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('attendance_document'))),
                'attendance_document',
                'DAFTAR_HADIR'
            );
        }

        foreach ($request->input('form_document', []) as $file) {
            $applicationResultSeminar->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'form_document',
                'FORM_PENILAIAN'
            );
        }

        if ($request->input('latest_script', false)) {
            $applicationResultSeminar->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                'latest_script',
                'NASKAH'
            );
        }

        foreach ($request->input('documentation', []) as $file) {
            $applicationResultSeminar->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'documentation',
                'DOKUMENTASI'
            );
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationResultSeminar->id]);
        }

        // Update the application stage and status
        if ($request->input('application_id')) {
            Application::where('id', $request->input('application_id'))->update([
                'stage' => 'seminar',
                'status' => 'result',
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('frontend.application-result-seminars.index');
    }

    public function edit(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultSeminar->load('application');

        return view('frontend.applicationResultSeminars.edit', compact('applicationResultSeminar'));
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
                $applicationResultSeminar->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'report_document',
                    'BA'
                );
            }
        }

        if ($request->input('attendance_document', false)) {
            if (! $applicationResultSeminar->attendance_document || $request->input('attendance_document') !== $applicationResultSeminar->attendance_document->file_name) {
                if ($applicationResultSeminar->attendance_document) {
                    $applicationResultSeminar->attendance_document->delete();
                }
                $applicationResultSeminar->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('attendance_document'))),
                    'attendance_document',
                    'DAFTAR_HADIR'
                );
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
                $applicationResultSeminar->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'form_document',
                    'FORM_PENILAIAN'
                );
            }
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultSeminar->latest_script || $request->input('latest_script') !== $applicationResultSeminar->latest_script->file_name) {
                if ($applicationResultSeminar->latest_script) {
                    $applicationResultSeminar->latest_script->delete();
                }
                $applicationResultSeminar->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                    'latest_script',
                    'NASKAH'
                );
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
                $applicationResultSeminar->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'documentation',
                    'DOKUMENTASI'
                );
            }
        }

        return redirect()->route('frontend.application-result-seminars.index');
    }

    public function show(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultSeminar->load('application');

        return view('frontend.applicationResultSeminars.show', compact('applicationResultSeminar'));
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
