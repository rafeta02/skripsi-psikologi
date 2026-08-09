<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Announcement;
use App\Models\Dosen;
use App\Services\ReviewerAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function resolveDosen(): Dosen
    {
        $user = Auth::user();

        $dosen = null;

        if ($user->dosen_id) {
            $dosen = Dosen::find($user->dosen_id);
        }

        if (!$dosen) {
            $dosen = Dosen::where('nip', $user->email)
                ->orWhere('nidn', $user->email)
                ->first();
        }

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi administrator.');
        }

        return $dosen;
    }

    private function authorizeAssignmentOwnership(ApplicationAssignment $assignment): void
    {
        $dosen = $this->resolveDosen();

        if ((int) $assignment->lecturer_id !== (int) $dosen->id) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $user = Auth::user();
        
        $dosen = null;
        
        if ($user->dosen_id) {
            $dosen = Dosen::find($user->dosen_id);
        }
        
        if (!$dosen) {
            $dosen = Dosen::where('nip', $user->email)
                ->orWhere('nidn', $user->email)
                ->first();
        }

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi administrator untuk mengatur profil dosen Anda.');
        }

        app(\App\Services\MbkmGroupProgressService::class)->purgeMirrorAssignments();

        app(ReviewerAssignmentService::class)->syncSupervisorInformantsForDosen($dosen->id);

        $totalMahasiswaBimbingan = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->where('role', 'supervisor')
            ->where('status', 'accepted')
            ->distinct('application_id')
            ->count('application_id');

        $totalTaskAssignments = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->count();

        $completedGuidance = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->where('role', 'supervisor')
            ->whereIn('status', ['accepted'])
            ->count();

        $pendingReviews = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->pendingAction()
            ->count();

        $totalTasksPending = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->pendingAction()
            ->count();

        $totalTasksCompleted = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->whereIn('status', ['accepted', 'rejected', 'feedback_submitted'])
            ->count();

        $totalScores = ApplicationScore::where('examiner_id', $dosen->id)->count();
        $pendingDefenseScores = ApplicationScore::where('examiner_id', $dosen->id)
            ->whereNull('score')
            ->where(function ($query) {
                $query->where(function ($preReport) {
                    $preReport->whereNotNull('application_id')
                        ->whereDoesntHave('application_result_defence');
                })->orWhereHas('application_result_defence', function ($resultQuery) {
                    $resultQuery->whereIn('result', ['passed', 'revision'])
                        ->whereHas('application.actions', function ($actionQuery) {
                            $actionQuery->where('action_type', 'result_defense_approved');
                        });
                });
            })
            ->count();

        $recentAssignments = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa',
                'application.skripsiRegistration',
                'application.mbkmRegistration.groupMembers.mahasiswa',
            ])
            ->where('lecturer_id', $dosen->id)
            ->orderBy('assigned_at', 'desc')
            ->limit(5)
            ->get();

        $recentAnnouncements = Announcement::recentForAudience('dosen');

        return view('dosen.dashboard', compact(
            'dosen',
            'totalMahasiswaBimbingan',
            'totalTaskAssignments',
            'completedGuidance',
            'pendingReviews',
            'totalTasksPending',
            'totalTasksCompleted',
            'totalScores',
            'pendingDefenseScores',
            'recentAssignments',
            'recentAnnouncements'
        ));
    }

    public function mahasiswaBimbingan()
    {
        $dosen = $this->resolveDosen();

        $mahasiswaBimbingan = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa.prodi',
                'application.mahasiswa.jenjang',
                'application.mbkmRegistration.groupMembers.mahasiswa',
                'application.assignments',
            ])
            ->where('lecturer_id', $dosen->id)
            ->where('role', 'supervisor')
            ->where('status', 'accepted')
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('dosen.mahasiswa-bimbingan', compact('mahasiswaBimbingan', 'dosen'));
    }

    public function taskAssignments()
    {
        $dosen = $this->resolveDosen();

        app(\App\Services\MbkmGroupProgressService::class)->purgeMirrorAssignments();

        app(ReviewerAssignmentService::class)->syncSupervisorInformantsForDosen($dosen->id);

        $assignments = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa.prodi',
                'application.mahasiswa.jenjang',
                'application.mbkmRegistration.groupMembers.mahasiswa',
                'application.skripsiSeminar',
            ])
            ->where('lecturer_id', $dosen->id)
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('dosen.task-assignments', compact('assignments', 'dosen'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        $dosen = null;
        
        if ($user->dosen_id) {
            $dosen = Dosen::with(['prodi', 'jenjang', 'fakultas', 'riset_grup', 'keilmuans'])
                ->find($user->dosen_id);
        }
        
        if (!$dosen) {
            $dosen = Dosen::with(['prodi', 'jenjang', 'fakultas', 'riset_grup', 'keilmuans'])
                ->where('nip', $user->email)
                ->orWhere('nidn', $user->email)
                ->first();
        }

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi administrator.');
        }

        return view('dosen.profile', compact('dosen'));
    }

    public function reviewProposal($assignmentId)
    {
        $assignment = ApplicationAssignment::with([
            'application.mahasiswa.user',
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'application.skripsiRegistration.themes',
            'application.skripsiRegistration.theme',
            'application.skripsiRegistration.preference_supervision',
            'application.skripsiRegistration.tps_lecturer',
            'application.skripsiSeminar',
            'application.mbkmRegistration.theme',
            'application.mbkmRegistration.themes',
            'application.mbkmRegistration.research_group',
            'application.mbkmRegistration.preference_supervision',
            'application.mbkmRegistration.groupMembers.mahasiswa',
            'application.mbkmRegistration.groupMembers.media',
            'application.mbkmRegistration.media',
            'skripsiSeminar',
        ])->findOrFail($assignmentId);

        $this->authorizeAssignmentOwnership($assignment);

        if ($assignment->application && $assignment->application->is_group_mirror) {
            $ownerId = $assignment->application->parent_application_id;
            $ownerAssignment = ApplicationAssignment::withoutGroupMirrors()
                ->where('application_id', $ownerId)
                ->where('lecturer_id', $assignment->lecturer_id)
                ->where('role', $assignment->role)
                ->orderByDesc('id')
                ->first();

            if ($ownerAssignment) {
                return redirect()->route('dosen.review-proposal', $ownerAssignment->id);
            }

            abort(404, 'Penugasan mirror tidak valid. Gunakan penugasan kelompok (ketua).');
        }

        return view('dosen.review-proposal', compact('assignment'));
    }

    public function respondToAssignment(Request $request, ApplicationAssignment $assignment)
    {
        $this->authorizeAssignmentOwnership($assignment);

        if ($assignment->application && $assignment->application->is_group_mirror) {
            abort(403, 'Penugasan mirror tidak dapat ditanggapi. Gunakan penugasan kelompok (ketua).');
        }

        if ($assignment->isProposalReviewer()) {
            return $this->handleProposalReviewerResponse($request, $assignment);
        }

        if ($assignment->isSupervisorAssignment()) {
            return $this->handleSupervisorResponse($request, $assignment);
        }

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:accepted,rejected',
                'note' => 'nullable|string',
            ]);

            $assignment->update([
                'status' => $request->status,
                'responded_at' => now(),
                'note' => $request->note,
            ]);

            if ($assignment->application && $assignment->role === 'supervisor') {
                $applicationStatus = $request->status === 'accepted' ? 'approved' : 'rejected';
                $assignment->application->update(['status' => $applicationStatus]);
            }

            $statusText = $request->status === 'accepted' ? 'menyetujui' : 'menolak';

            return redirect()->back()->with('message', "Anda berhasil {$statusText} penugasan.");
        }

        $validated = $request->validate([
            'review_decision' => 'required|in:approved,rejected',
            'feedback' => 'required|string',
        ]);

        $assignmentStatus = $validated['review_decision'] === 'rejected' ? 'rejected' : 'accepted';

        $assignment->update([
            'status' => $assignmentStatus,
            'note' => $validated['feedback'],
            'responded_at' => now(),
        ]);

        if ($assignment->application && $assignment->role === 'supervisor') {
            $assignment->application->update([
                'status' => $validated['review_decision'] === 'approved' ? 'approved' : 'rejected',
            ]);
        }

        $message = $validated['review_decision'] === 'approved'
            ? 'Penugasan diterima.'
            : 'Penugasan ditolak.';

        return redirect()->route('dosen.task-assignments')
            ->with('success', $message);
    }

    private function handleProposalReviewerResponse(Request $request, ApplicationAssignment $assignment)
    {
        $action = $request->input('action', 'respond_assignment');

        if ($action === 'submit_feedback') {
            if (!$assignment->canSubmitFeedback()) {
                return redirect()->back()->with('error', 'Anda tidak dapat mengirim feedback pada status penugasan ini atau batas waktu telah lewat.');
            }

            $mimes = config('thesis.reviewer_feedback_mimes', 'pdf,doc,docx');
            $maxKb = (int) config('thesis.reviewer_feedback_max_kb', 10240);

            $validated = $request->validate([
                'feedback_result' => 'required|in:passed,revision,failed',
                'feedback_note' => 'required|string|min:10',
                'feedback_document' => "required|file|mimes:{$mimes}|max:{$maxKb}",
            ]);

            $assignment->update([
                'status' => 'feedback_submitted',
                'feedback_result' => $validated['feedback_result'],
                'feedback_note' => $validated['feedback_note'],
                'feedback_submitted_at' => now(),
            ]);

            if ($assignment->feedback_document) {
                $assignment->feedback_document->delete();
            }

            $assignment->addMedia($request->file('feedback_document'))
                ->toMediaCollection('feedback_document');

            app(ReviewerAssignmentService::class)->syncApplicationReviewStatus($assignment->application);

            return redirect()->route('dosen.task-assignments')
                ->with('success', 'Feedback review proposal berhasil dikirim.');
        }

        if (!$assignment->canRespondToAssignment()) {
            return redirect()->back()->with('error', 'Batas waktu respons penugasan telah lewat atau penugasan sudah ditanggapi.');
        }

        $validated = $request->validate([
            'assignment_response' => 'required|in:accepted,rejected',
            'rejection_reason' => 'required_if:assignment_response,rejected|nullable|string|min:10',
        ]);

        if ($validated['assignment_response'] === 'accepted') {
            $assignment->update([
                'status' => 'accepted',
                'responded_at' => now(),
            ]);
        } else {
            $assignment->update([
                'status' => 'rejected',
                'responded_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);
        }

        app(ReviewerAssignmentService::class)->syncApplicationReviewStatus($assignment->application);

        $message = $validated['assignment_response'] === 'accepted'
            ? 'Anda menerima penugasan review. Silakan kirim feedback maksimal '
                . config('thesis.reviewer_feedback_deadline_days', 14) . ' hari sejak penugasan.'
            : 'Penugasan review ditolak.';

        return redirect()->route('dosen.review-proposal', $assignment->id)
            ->with('success', $message);
    }

    private function handleSupervisorResponse(Request $request, ApplicationAssignment $assignment)
    {
        if ($assignment->status !== 'assigned') {
            return redirect()->back()->with('error', 'Penugasan pembimbingan ini sudah ditanggapi.');
        }

        if ($request->filled('review_decision')) {
            $validated = $request->validate([
                'review_decision' => 'required|in:approved,rejected',
                'feedback' => 'nullable|string',
            ]);

            $response = $validated['review_decision'] === 'approved' ? 'accepted' : 'rejected';
            $note = $validated['feedback'] ?? null;
            $rejectionReason = $response === 'rejected' ? $note : null;
        } else {
            $validated = $request->validate([
                'supervisor_response' => 'required|in:accepted,rejected',
                'rejection_reason' => 'required_if:supervisor_response,rejected|nullable|string|min:10',
                'note' => 'nullable|string',
            ]);

            $response = $validated['supervisor_response'];
            $note = $validated['note'] ?? null;
            $rejectionReason = $response === 'rejected'
                ? ($validated['rejection_reason'] ?? $note)
                : null;
        }

        $assignment->update([
            'status' => $response,
            'responded_at' => now(),
            'note' => $note,
            'rejection_reason' => $rejectionReason,
        ]);

        if ($assignment->application) {
            $assignment->application->update([
                'status' => $response === 'accepted' ? 'approved' : 'rejected',
            ]);
        }

        $message = $response === 'accepted'
            ? 'Anda menerima permintaan pembimbingan.'
            : 'Permintaan pembimbingan ditolak.';

        return redirect()->route('dosen.task-assignments')->with('success', $message);
    }

}
