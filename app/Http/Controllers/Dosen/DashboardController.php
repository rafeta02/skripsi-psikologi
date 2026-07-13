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

        app(\App\Services\MbkmGroupProgressService::class)->purgeMirrorAssignments();

        // Statistics — exclude MBKM mirror applications (1 kelompok = 1 penugasan)
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
            ->where('status', 'assigned')
            ->count();

        $totalTasksPending = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->where('status', 'assigned')
            ->count();

        $totalTasksCompleted = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosen->id)
            ->whereIn('status', ['accepted', 'rejected'])
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

        // Recent assignments
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
            'recentAssignments'
        ));
    }

    public function mahasiswaBimbingan()
    {
        $dosen = $this->resolveDosen();

        // Get all students under supervision (exclude MBKM mirrors)
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

        $assignments = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa.prodi',
                'application.mbkmRegistration.groupMembers.mahasiswa',
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
            'application.skripsiRegistration.theme',
            'application.skripsiRegistration.preference_supervision',
            'application.skripsiRegistration.tps_lecturer',
            'application.mbkmRegistration.theme',
            'application.mbkmRegistration.themes',
            'application.mbkmRegistration.research_group',
            'application.mbkmRegistration.preference_supervision',
            'application.mbkmRegistration.groupMembers.mahasiswa',
            'application.mbkmRegistration.groupMembers.media',
            'application.mbkmRegistration.media',
        ])->findOrFail($assignmentId);

        $this->authorizeAssignmentOwnership($assignment);

        // Mirror assignment should not be reviewed — redirect to owner assignment if any
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
            'review_decision' => 'required|in:approved,rejected',
            'feedback' => 'required|string',
        ]);

        $assignmentStatus = $validated['review_decision'] === 'rejected' ? 'rejected' : 'accepted';

        $assignment->update([
            'status' => $assignmentStatus,
            'note' => $validated['feedback'],
            'responded_at' => now(),
        ]);

        if ($assignment->application) {
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

}
