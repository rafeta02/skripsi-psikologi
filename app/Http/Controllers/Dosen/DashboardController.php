<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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

        $totalTasksPending = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->where('status', 'assigned')
            ->count();

        $totalTasksCompleted = ApplicationAssignment::where('lecturer_id', $dosen->id)
            ->whereIn('status', ['accepted', 'rejected'])
            ->count();

        $totalScores = ApplicationScore::where('examiner_id', $dosen->id)->count();

        // Recent assignments
        $recentAssignments = ApplicationAssignment::with(['application.mahasiswa', 'application'])
            ->where('lecturer_id', $dosen->id)
            ->orderBy('assigned_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalMahasiswaBimbingan',
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

    public function respondToAssignment(Request $request, ApplicationAssignment $assignment)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'note' => 'nullable|string'
        ]);

        $assignment->update([
            'status' => $request->status,
            'responded_at' => now(), // Mutator will handle Carbon object
            'note' => $request->note
        ]);

        $statusText = $request->status === 'accepted' ? 'menyetujui' : 'menolak';
        
        return redirect()->back()->with('message', "Anda berhasil {$statusText} penugasan pembimbingan.");
    }
}
