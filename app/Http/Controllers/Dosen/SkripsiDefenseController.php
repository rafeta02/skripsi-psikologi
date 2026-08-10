<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\SkripsiDefense;
use App\Services\DefenseScoringService;
use Illuminate\Support\Facades\Auth;

class SkripsiDefenseController extends Controller
{
    public function __construct(
        private readonly DefenseScoringService $defenseScoringService
    ) {
    }

    private function resolveDosen(): Dosen
    {
        $user = Auth::user();

        $dosen = null;

        if ($user->dosen_id) {
            $dosen = Dosen::find($user->dosen_id);
        }

        if (! $dosen) {
            $dosen = Dosen::where('nip', $user->email)
                ->orWhere('nidn', $user->email)
                ->first();
        }

        if (! $dosen) {
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi administrator.');
        }

        return $dosen;
    }

    private function authorizeDefenseAccess(SkripsiDefense $skripsiDefense): Dosen
    {
        $dosen = $this->resolveDosen();
        $application = $skripsiDefense->application;

        if (! $application || ! $this->defenseScoringService->canDosenViewDefenseManuscript($application, $dosen->id)) {
            abort(403, 'Anda tidak memiliki akses ke naskah sidang ini.');
        }

        return $dosen;
    }

    public function index()
    {
        $dosen = $this->resolveDosen();
        $defenses = $this->defenseScoringService->getViewableDefensesForDosen($dosen->id);

        return view('dosen.skripsi-defenses.index', [
            'defenses' => $defenses,
            'dosen' => $dosen,
        ]);
    }

    public function show(int $skripsiDefenseId)
    {
        $skripsiDefense = SkripsiDefense::queryForDosenPortal()->findOrFail($skripsiDefenseId);

        $skripsiDefense->load([
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'examiner1.dosen',
            'examiner2.dosen',
        ]);

        $dosen = $this->authorizeDefenseAccess($skripsiDefense);
        $application = $skripsiDefense->application;
        $schedule = $this->defenseScoringService->resolveDefenseSchedule($application);
        $schedule?->load('ruang');
        $defenseHeld = $application
            ? $this->defenseScoringService->isDefenseHeld($application)
            : false;
        $canScore = $application
            ? $this->defenseScoringService->canDosenScore($application, $dosen->id)
            : false;
        $scoreAssignment = $application
            ? $this->defenseScoringService->findScoreAssignmentForApplication($application, $dosen->id)
            : null;
        $resultDefense = $application
            ? \App\Models\ApplicationResultDefense::where('application_id', $application->id)->first()
            : null;

        return view('dosen.skripsi-defenses.show', [
            'skripsiDefense' => $skripsiDefense,
            'dosen' => $dosen,
            'dosenRole' => $skripsiDefense->dosenRoleLabel($dosen->id),
            'schedule' => $schedule,
            'scheduleVerified' => $schedule?->isDefenseScheduleVerified() ?? false,
            'defenseHeld' => $defenseHeld,
            'canScore' => $canScore,
            'scoreAssignment' => $scoreAssignment,
            'resultDefense' => $resultDefense,
            'canViewResultReport' => $application && $resultDefense
                && $this->defenseScoringService->canDosenViewDefenseResultReport($application, $dosen->id, $resultDefense),
        ]);
    }
}
