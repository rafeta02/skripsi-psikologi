<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiSeminarRequest;
use App\Http\Requests\StoreSkripsiSeminarRequest;
use App\Http\Requests\UpdateSkripsiSeminarRequest;
use App\Models\Application;
use App\Models\ApplicationResultSeminar;
use App\Models\SkripsiSeminar;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SkripsiSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('skripsi_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $formAccessService = new FormAccessService();
        $retrySeminar = $formAccessService->getSkripsiSeminarForFailedRetry($mahasiswaId);

        $skripsiSeminars = SkripsiSeminar::with(['application', 'reviewer1', 'reviewer2'])
            ->whereHas('application', fn ($q) => $q->where('mahasiswa_id', $mahasiswaId))
            ->orderByDesc('created_at')
            ->get();

        return view('frontend.skripsiSeminars.index', compact('skripsiSeminars', 'retrySeminar'));
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            if (!empty($access['retry_seminar'])) {
                return redirect()
                    ->route('frontend.skripsi-seminars.edit', $access['retry_seminar']->id)
                    ->with('warning', $access['message']);
            }

            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.skripsiSeminars.create', compact('activeApplication'));
    }

    public function store(StoreSkripsiSeminarRequest $request)
    {
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            if (!empty($access['retry_seminar'])) {
                return redirect()
                    ->route('frontend.skripsi-seminars.edit', $access['retry_seminar']->id)
                    ->with('warning', $access['message']);
            }

            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        $seminarApplication = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => 'skripsi',
            'stage' => 'seminar',
            'status' => 'submitted',
            'submitted_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $data = $request->only(['title', 'description', 'notes']);
        $data['application_id'] = $seminarApplication->id;

        $skripsiSeminar = SkripsiSeminar::create($data);

        if ($request->hasFile('proposal_document')) {
            $skripsiSeminar->addMedia($request->file('proposal_document'))
                ->toMediaCollection('proposal_document');
        }

        if ($request->hasFile('approval_document')) {
            $skripsiSeminar->addMedia($request->file('approval_document'))
                ->toMediaCollection('approval_document');
        }

        if ($request->hasFile('plagiarism_document')) {
            $skripsiSeminar->addMedia($request->file('plagiarism_document'))
                ->toMediaCollection('plagiarism_document');
        }

        return redirect()->route('frontend.skripsi-seminars.index')
            ->with('success', 'Pendaftaran reviewer proposal berhasil dikirim!');
    }

    public function edit(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canEditSkripsiSeminar($skripsiSeminar, $mahasiswaId);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        $skripsiSeminar->load('application', 'reviewer1', 'reviewer2');

        return view('frontend.skripsiSeminars.edit', [
            'skripsiSeminar' => $skripsiSeminar,
            'retryAfterFailed' => $access['retry_after_failed'],
        ]);
    }

    public function update(UpdateSkripsiSeminarRequest $request, SkripsiSeminar $skripsiSeminar)
    {
        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canEditSkripsiSeminar($skripsiSeminar, $mahasiswaId);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        $retryAfterFailed = $access['retry_after_failed'];

        DB::transaction(function () use ($request, $skripsiSeminar, $retryAfterFailed) {
            $updateData = ['title' => $request->title];

            if ($retryAfterFailed) {
                $updateData['reviewer_1_id'] = null;
                $updateData['reviewer_2_id'] = null;
            }

            $skripsiSeminar->update($updateData);

            if ($request->hasFile('proposal_document')) {
                $skripsiSeminar->clearMediaCollection('proposal_document');
                $skripsiSeminar->addMedia($request->file('proposal_document'))
                    ->toMediaCollection('proposal_document');
            }

            if ($request->hasFile('approval_document')) {
                $skripsiSeminar->clearMediaCollection('approval_document');
                $skripsiSeminar->addMedia($request->file('approval_document'))
                    ->toMediaCollection('approval_document');
            }

            if ($request->hasFile('plagiarism_document')) {
                $skripsiSeminar->clearMediaCollection('plagiarism_document');
                $skripsiSeminar->addMedia($request->file('plagiarism_document'))
                    ->toMediaCollection('plagiarism_document');
            }

            if ($retryAfterFailed && $skripsiSeminar->application) {
                ApplicationResultSeminar::where('application_id', $skripsiSeminar->application_id)->delete();

                $skripsiSeminar->application->update([
                    'status' => 'submitted',
                    'notes' => null,
                ]);
            }
        });

        $message = $retryAfterFailed
            ? 'Pendaftaran reviewer berhasil diperbarui. Penugasan reviewer direset; menunggu verifikasi admin.'
            : 'Pendaftaran reviewer proposal berhasil diperbarui.';

        return redirect()->route('frontend.skripsi-seminars.index')
            ->with('success', $message);
    }

    public function show(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if ($skripsiSeminar->application && (int) $skripsiSeminar->application->mahasiswa_id !== (int) $mahasiswaId) {
            abort(403);
        }

        $skripsiSeminar->load('application', 'reviewer1', 'reviewer2');
        $reviewerAssignments = $skripsiSeminar->activeReviewerAssignments();
        $reviewStatus = $skripsiSeminar->mahasiswaReviewStatus();
        $formAccessService = new FormAccessService();
        $canEdit = $formAccessService->canEditSkripsiSeminar($skripsiSeminar, $mahasiswaId);

        return view('frontend.skripsiSeminars.show', [
            'skripsiSeminar' => $skripsiSeminar,
            'reviewerAssignments' => $reviewerAssignments,
            'reviewStatus' => $reviewStatus,
            'canEdit' => $canEdit,
        ]);
    }

    public function destroy(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiSeminarRequest $request)
    {
        $skripsiSeminars = SkripsiSeminar::find(request('ids'));

        foreach ($skripsiSeminars as $skripsiSeminar) {
            $skripsiSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_seminar_create') && Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
