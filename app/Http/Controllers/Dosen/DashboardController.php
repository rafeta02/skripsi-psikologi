<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Dosen;
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
        
        // Get dosen data by user - prioritize dosen_id relationship
        $dosen = null;
        
        if ($user->dosen_id) {
            $dosen = Dosen::find($user->dosen_id);
        }
        
        // If no dosen_id, try to find by email matching NIP/NIDN
        if (!$dosen) {
            $dosen = Dosen::where('nip', $user->email)
                ->orWhere('nidn', $user->email)
                ->first();
        }

        if (!$dosen) {
            // If still not found, show error
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi administrator untuk mengatur profil dosen Anda.');
        }

        // Statistics
        $totalMahasiswaBimbingan = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->where('role', 'supervisor')
            ->where('status', 'accepted')
            ->distinct('application_id')
            ->count();

        $totalTaskAssignments = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->count();

        $completedGuidance = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->where('role', 'supervisor')
            ->whereIn('status', ['accepted'])
            ->count();

        $pendingReviews = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->where('status', 'assigned')
            ->count();

        $totalTasksPending = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->where('status', 'assigned')
            ->count();

        $totalTasksCompleted = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->whereIn('status', ['accepted', 'rejected'])
            ->count();

        $totalScores = ApplicationScore::where('examiner_id', $dosen->id)->count();

        // Recent assignments
        $recentAssignments = ApplicationAssignment::with([
            'application.mahasiswa', 
            'application.skripsiRegistration',
            'application.mbkmRegistration'
        ])
            ->where('lecturer_id', $dosen->id)
            ->orderBy('assigned_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalMahasiswaBimbingan',
            'totalTaskAssignments',
            'completedGuidance',
            'pendingReviews',
            'totalTasksPending',
            'totalTasksCompleted',
            'totalScores',
            'recentAssignments'
        ));
    }

    public function mahasiswaBimbingan()
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

        // Get all students under supervision
        $mahasiswaBimbingan = ApplicationAssignment::with([
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'application'
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

        // Get all task assignments
        $assignments = ApplicationAssignment::with([
            'application.mahasiswa.prodi',
            'application'
        ])
            ->where('lecturer_id', $dosen->id)
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('dosen.task-assignments', compact('assignments', 'dosen'));
    }

    public function scores()
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

        // Get all scores given by this dosen
        $scores = ApplicationScore::with([
            'application_result_defence.application.mahasiswa.prodi',
            'application_result_defence.application'
        ])
            ->where('examiner_id', $dosen->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.scores', compact('scores', 'dosen'));
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
            'application.skripsiRegistration.theme',
            'application.skripsiRegistration.preference_supervision',
            'application.skripsiRegistration.tps_lecturer',
            'application.mbkmRegistration.theme',
            'application.mbkmRegistration.research_group',
            'application.mbkmRegistration.preference_supervision',
            'application.mbkmRegistration.groupMembers.mahasiswa',
        ])->findOrFail($assignmentId);

        $this->authorizeAssignmentOwnership($assignment);

        return view('dosen.review-proposal', compact('assignment'));
    }

    public function respondToAssignment(Request $request, ApplicationAssignment $assignment)
    {
        $this->authorizeAssignmentOwnership($assignment);

        // If it's a simple accept/reject (old flow)
        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:accepted,rejected',
                'note' => 'nullable|string'
            ]);

            $assignment->update([
                'status' => $request->status,
                'responded_at' => now(),
                'note' => $request->note
            ]);

            if ($assignment->application) {
                $applicationStatus = $request->status === 'accepted' ? 'approved' : 'rejected';
                $assignment->application->update(['status' => $applicationStatus]);
            }

            $statusText = $request->status === 'accepted' ? 'menyetujui' : 'menolak';
            return redirect()->back()->with('message', "Anda berhasil {$statusText} penugasan pembimbingan.");
        }

        // If it's a review with decision (new flow)
        $validated = $request->validate([
            'review_decision' => 'required|in:approved,revision,rejected',
            'feedback' => 'required|string',
            'revision_notes' => 'nullable|string',
        ]);

        $assignmentStatus = $validated['review_decision'] === 'rejected' ? 'rejected' : 'accepted';

        $note = $validated['feedback'];
        if ($validated['review_decision'] === 'revision' && !empty($validated['revision_notes'])) {
            $note .= "\n\nCatatan revisi:\n" . $validated['revision_notes'];
        }

        $assignment->update([
            'status' => $assignmentStatus,
            'note' => $note,
            'responded_at' => now(),
        ]);

        // Update application status based on review decision
        if ($assignment->application) {
            $newStatus = match($validated['review_decision']) {
                'approved' => 'approved',
                'revision' => 'revision',
                'rejected' => 'rejected',
                default => 'submitted',
            };

            $assignment->application->update(['status' => $newStatus]);
        }

        $message = match($validated['review_decision']) {
            'approved' => 'Penugasan diterima dan proposal disetujui.',
            'revision' => 'Penugasan diterima. Mahasiswa diminta melakukan revisi.',
            'rejected' => 'Penugasan ditolak.',
            default => 'Review berhasil dikirim.',
        };

        return redirect()->route('dosen.task-assignments')
            ->with('success', $message);
    }

    public function scoring($applicationId)
    {
        $application = Application::with([
            'mahasiswa',
            'skripsiDefense'
        ])->findOrFail($applicationId);

        $dosen = $this->resolveDosen();
        $hasAccess = ApplicationAssignment::where('application_id', $application->id)
            ->where('lecturer_id', $dosen->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        return view('dosen.scoring', compact('application'));
    }

    public function storeScoring(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);

        $dosen = $this->resolveDosen();
        $hasAccess = ApplicationAssignment::where('application_id', $application->id)
            ->where('lecturer_id', $dosen->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        // Validate
        $validated = $request->validate([
            'overall_score' => 'required|numeric|min:0|max:100',
            'presentation_score' => 'nullable|numeric|min:0|max:100',
            'content_score' => 'nullable|numeric|min:0|max:100',
            'methodology_score' => 'nullable|numeric|min:0|max:100',
            'qa_score' => 'nullable|numeric|min:0|max:100',
            'grade_letter' => 'nullable|string|max:5',
            'comments' => 'required|string',
            'recommendation' => 'required|in:passed,passed_with_revision,failed',
        ]);

        // Create or update ApplicationScore
        $score = ApplicationScore::updateOrCreate(
            [
                'application_id' => $application->id,
                'examiner_id' => $dosen->id,
            ],
            array_merge($validated, [
                'scored_at' => now(),
            ])
        );

        return redirect()->route('dosen.scores')
            ->with('success', 'Penilaian berhasil disimpan!');
    }
}
