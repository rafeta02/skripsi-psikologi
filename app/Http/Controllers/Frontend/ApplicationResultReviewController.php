<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Application;
use App\Models\ApplicationResultReview;
use App\Services\ReviewerAssignmentService;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationResultReviewController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_result_review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $applicationIds = Application::where('mahasiswa_id', $mahasiswaId)->pluck('id');

        $applicationResultReviews = ApplicationResultReview::with(['application'])
            ->whereIn('application_id', $applicationIds)
            ->orderByDesc('created_at')
            ->get();

        $formAccessService = new FormAccessService();
        $canCreate = $formAccessService->canAccessApplicationResultReview($mahasiswaId, true);

        return view('frontend.applicationResultReviews.index', compact('applicationResultReviews', 'canCreate'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessApplicationResultReview($mahasiswaId, true);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-reviews.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.applicationResultReviews.create', compact('activeApplication'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('application_result_review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessApplicationResultReview($mahasiswaId, true);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-reviews.index')
                ->with('error', $access['message']);
        }

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'result' => 'required|in:passed,revision,failed',
            'note' => 'nullable|string',
            'revision_deadline' => 'nullable|date',
            'form_document' => 'nullable|array',
            'form_document.*' => 'file|mimes:pdf|max:10240',
            'latest_script' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ((int) $validated['application_id'] !== (int) $access['application']->id) {
            abort(403, 'Aplikasi tidak valid.');
        }

        $ownsApplication = Application::where('id', $validated['application_id'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if (!$ownsApplication) {
            abort(403, 'Unauthorized');
        }

        $reviewerService = app(ReviewerAssignmentService::class);
        $assignments = $reviewerService->activeReviewerAssignments($validated['application_id'])
            ->where('status', 'feedback_submitted')
            ->sortBy('reviewer_slot')
            ->values();

        $applicationResultReview = ApplicationResultReview::create([
            'application_id' => $validated['application_id'],
            'reviewer_1_assignment_id' => $assignments->get(0)?->id,
            'reviewer_2_assignment_id' => $assignments->get(1)?->id,
            'result' => $validated['result'],
            'note' => $validated['note'] ?? null,
            'revision_deadline' => $validated['revision_deadline'] ?? null,
        ]);

        if ($request->hasFile('form_document')) {
            foreach ($request->file('form_document') as $file) {
                $applicationResultReview->addMedia($file)->toMediaCollection('form_document');
            }
        }

        if ($request->hasFile('latest_script')) {
            $applicationResultReview->addMedia($request->file('latest_script'))
                ->toMediaCollection('latest_script');
        }

        $applicationResultReview->syncApplicationStatus();

        $message = $validated['result'] === 'passed'
            ? 'Laporan hasil lulus berhasil dikirim. Menunggu validasi admin sebelum Anda dapat mendaftar sidang skripsi.'
            : 'Laporan hasil review proposal berhasil dikirim!';

        return redirect()->route('frontend.application-result-reviews.index')
            ->with('success', $message);
    }

    public function show(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->authorizeOwnership($applicationResultReview);

        $applicationResultReview->load('application');
        $applicationResultReview->syncApplicationStatus();

        $formAccessService = new FormAccessService();
        $canAccessDefense = $formAccessService->canAccessSkripsiDefense(auth()->user()->mahasiswa_id);

        return view('frontend.applicationResultReviews.show', compact(
            'applicationResultReview',
            'canAccessDefense'
        ));
    }

    protected function authorizeOwnership(ApplicationResultReview $applicationResultReview): void
    {
        $mahasiswaId = auth()->user()->mahasiswa_id;
        $owns = Application::where('id', $applicationResultReview->application_id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if (!$owns) {
            abort(403, 'Unauthorized');
        }
    }
}
