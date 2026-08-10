<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ApplicationResultDefense;
use App\Models\Dosen;
use App\Services\DefenseScoringService;
use Illuminate\Support\Facades\Auth;

class ApplicationResultDefenseController extends Controller
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

    private function authorizeResultAccess(ApplicationResultDefense $resultDefense): Dosen
    {
        $dosen = $this->resolveDosen();
        $application = $resultDefense->application;

        if (! $application || ! $this->defenseScoringService->canDosenViewDefenseResultReport($application, $dosen->id, $resultDefense)) {
            abort(403, 'Anda tidak memiliki akses ke laporan hasil sidang ini.');
        }

        return $dosen;
    }

    public function index()
    {
        $dosen = $this->resolveDosen();
        $resultDefenses = $this->defenseScoringService->getViewableDefenseResultsForDosen($dosen->id);

        return view('dosen.application-result-defenses.index', [
            'resultDefenses' => $resultDefenses,
            'dosen' => $dosen,
        ]);
    }

    public function show(int $applicationResultDefenseId)
    {
        $resultDefense = ApplicationResultDefense::query()->findOrFail($applicationResultDefenseId);

        $resultDefense->load([
            'application.mahasiswa.prodi',
            'application.mahasiswa.jenjang',
            'application.skripsiDefense',
            'scores.examiner',
        ]);

        $dosen = $this->authorizeResultAccess($resultDefense);

        $skripsiDefense = $resultDefense->application_id
            ? \App\Models\SkripsiDefense::queryForDosenPortal()
                ->where('application_id', $resultDefense->application_id)
                ->first()
            : null;

        return view('dosen.application-result-defenses.show', [
            'resultDefense' => $resultDefense,
            'dosen' => $dosen,
            'dosenRole' => $skripsiDefense?->dosenRoleLabel($dosen->id),
        ]);
    }
}
