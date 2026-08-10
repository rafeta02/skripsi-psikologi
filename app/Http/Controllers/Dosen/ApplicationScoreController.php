<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationScore;
use App\Models\Dosen;
use App\Services\DefenseScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationScoreController extends Controller
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

    private function resolveApplication(ApplicationScore $applicationScore): ?Application
    {
        if ($applicationScore->application_id) {
            return $applicationScore->application;
        }

        return $applicationScore->application_result_defence?->application;
    }

    private function authorizeScoreOwnership(ApplicationScore $applicationScore): void
    {
        $dosen = $this->resolveDosen();

        if ((int) $applicationScore->examiner_id !== (int) $dosen->id) {
            abort(403, 'Unauthorized');
        }

        $application = $this->resolveApplication($applicationScore);

        if (! $application) {
            abort(403, 'Data aplikasi sidang tidak ditemukan.');
        }

        if ($this->defenseScoringService->canDosenScore($application, $dosen->id)) {
            return;
        }

        abort(403, 'Penilaian sidang belum tersedia. Sidang harus sudah dilaksanakan.');
    }

    public function index()
    {
        $dosen = $this->resolveDosen();
        $scores = $this->defenseScoringService->getScoreAssignmentsForDosen($dosen->id);
        $pendingCount = $scores->filter(fn ($score) => ! $score->isComplete())->count();

        return view('dosen.scores', compact('scores', 'dosen', 'pendingCount'));
    }

    public function edit(ApplicationScore $applicationScore)
    {
        $this->authorizeScoreOwnership($applicationScore);

        $applicationScore->load([
            'application.mahasiswa.prodi',
            'application_result_defence.application.mahasiswa.prodi',
            'examiner',
        ]);

        $application = $this->resolveApplication($applicationScore);
        $skripsiDefense = $application
            ? \App\Models\SkripsiDefense::queryForDosenPortal()
                ->where('application_id', $application->id)
                ->first()
            : null;

        $dosen = $this->resolveDosen();

        return view('dosen.application-scores.edit', compact(
            'applicationScore',
            'dosen',
            'skripsiDefense'
        ));
    }

    public function update(Request $request, ApplicationScore $applicationScore)
    {
        $this->authorizeScoreOwnership($applicationScore);

        $validated = $request->validate([
            'penulisan' => 'required|integer|min:0|max:100',
            'isi' => 'required|integer|min:0|max:100',
            'analisis' => 'required|integer|min:0|max:100',
            'teoritis' => 'required|integer|min:0|max:100',
            'faktual' => 'required|integer|min:0|max:100',
            'pemecahan_masalah' => 'required|integer|min:0|max:100',
            'penyampaian' => 'required|integer|min:0|max:100',
            'note' => 'nullable|string|max:5000',
        ]);

        $components = [
            $validated['penulisan'],
            $validated['isi'],
            $validated['analisis'],
            $validated['teoritis'],
            $validated['faktual'],
            $validated['pemecahan_masalah'],
            $validated['penyampaian'],
        ];

        $sum = array_sum($components);
        $score = round($sum / count($components), 2);

        $applicationScore->update(array_merge($validated, [
            'sum' => $sum,
            'score' => $score,
        ]));

        return redirect()->route('dosen.scores')
            ->with('success', 'Penilaian sidang berhasil disimpan.');
    }
}
