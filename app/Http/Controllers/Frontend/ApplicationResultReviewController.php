<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultReviewRequest;
use App\Http\Requests\StoreApplicationResultReviewRequest;
use App\Http\Requests\UpdateApplicationResultReviewRequest;
use App\Models\Application;
use App\Models\ApplicationResultReview;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ApplicationResultReviewController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_result_review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReviews = ApplicationResultReview::with(['application', 'media'])->get();

        return view('frontend.applicationResultReviews.index', compact('applicationResultReviews'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        return view('frontend.applicationResultReviews.create', compact('activeApplication'));
    }

    public function store(StoreApplicationResultReviewRequest $request)
    {
        $applicationResultReview = ApplicationResultReview::create($request->all());

        foreach ($request->input('form_document', []) as $file) {
            $applicationResultReview->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'form_document',
                'FORM_PENILAIAN'
            );
        }

        if ($request->input('latest_script', false)) {
            $applicationResultReview->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                'latest_script',
                'NASKAH'
            );
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationResultReview->id]);
        }

        // Update the application stage and status
        if ($request->input('application_id')) {
            Application::where('id', $request->input('application_id'))->update([
                'stage' => 'review',
                'status' => 'result',
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('frontend.application-result-reviews.index')
            ->with('success', 'Hasil review proposal berhasil disimpan!');
    }

    public function edit(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReview->load('application');

        return view('frontend.applicationResultReviews.edit', compact('applicationResultReview'));
    }

    public function update(UpdateApplicationResultReviewRequest $request, ApplicationResultReview $applicationResultReview)
    {
        $applicationResultReview->update($request->all());

        if (count($applicationResultReview->form_document) > 0) {
            foreach ($applicationResultReview->form_document as $media) {
                if (! in_array($media->file_name, $request->input('form_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultReview->form_document->pluck('file_name')->toArray();
        foreach ($request->input('form_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultReview->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'form_document',
                    'FORM_PENILAIAN'
                );
            }
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultReview->latest_script || $request->input('latest_script') !== $applicationResultReview->latest_script->file_name) {
                if ($applicationResultReview->latest_script) {
                    $applicationResultReview->latest_script->delete();
                }
                $applicationResultReview->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                    'latest_script',
                    'NASKAH'
                );
            }
        } elseif ($applicationResultReview->latest_script) {
            $applicationResultReview->latest_script->delete();
        }

        return redirect()->route('frontend.application-result-reviews.index')
            ->with('success', 'Hasil review proposal berhasil diperbarui!');
    }

    public function show(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReview->load('application');

        return view('frontend.applicationResultReviews.show', compact('applicationResultReview'));
    }

    public function destroy(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReview->delete();

        return back()->with('success', 'Hasil review berhasil dihapus!');
    }

    public function massDestroy(MassDestroyApplicationResultReviewRequest $request)
    {
        $applicationResultReviews = ApplicationResultReview::find(request('ids'));

        foreach ($applicationResultReviews as $applicationResultReview) {
            $applicationResultReview->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_result_review_create') && Gate::denies('application_result_review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationResultReview();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}


