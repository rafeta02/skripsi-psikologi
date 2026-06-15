<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationResultDefense;
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

        if (!$application) {
            abort(403, 'Data aplikasi sidang tidak ditemukan.');
        }

        // Alur baru: penilaian setelah sidang dilaksanakan, sebelum mahasiswa lapor hasil
        if ($this->defenseScoringService->canDosenScore($application, $dosen->id)) {
            return;
        }

        // Alur legacy: penilaian setelah admin validasi laporan hasil sidang
        $resultDefense = $applicationScore->application_result_defence;

        if ($resultDefense && $resultDefense->isValidatedByAdmin()) {
            if ($resultDefense->result === 'failed') {
                abort(403, 'Penilaian tidak diperlukan untuk hasil sidang tidak lulus.');
            }

            return;
        }

        abort(403, 'Penilaian sidang belum tersedia. Sidang harus sudah dilaksanakan.');
    }

    public function index()
    {
        $dosen = $this->resolveDosen();
        $this->defenseScoringService->syncAssignmentsForDosen($dosen->id);

        $scores = ApplicationScore::with([
            'application.mahasiswa.prodi',
            'application.skripsiDefense',
            'application_result_defence.application.mahasiswa.prodi',
        ])
            ->where('examiner_id', $dosen->id)
            ->where(function ($query) {
                $query->where(function ($preReport) {
                    $preReport->whereNotNull('application_id')
                        ->whereDoesntHave('application_result_defence');
                })->orWhereHas('application_result_defence', function ($resultQuery) {
                    $resultQuery->whereIn('result', ['passed', 'revision']);
                });
            })
            ->orderByRaw('CASE WHEN score IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function (ApplicationScore $score) use ($dosen) {
                $application = $this->resolveApplication($score);

                if (!$application) {
                    return false;
                }

                if ($score->application_result_defence) {
                    return $score->application_result_defence->isValidatedByAdmin();
                }

                return $this->defenseScoringService->canDosenScore($application, $dosen->id)
                    || $this->defenseScoringService->isDefenseHeld($application);
            })
            ->values();

        $pendingCount = $scores->filter(fn ($s) => !$s->isComplete())->count();

        return view('dosen.scores', compact('scores', 'dosen', 'pendingCount'));
    }

    public function edit(ApplicationScore $applicationScore)
    {
        $this->authorizeScoreOwnership($applicationScore);

        $applicationScore->load([
            'application.mahasiswa.prodi',
            'application.skripsiDefense',
            'application_result_defence.application.mahasiswa.prodi',
            'examiner',
        ]);

        $dosen = $this->resolveDosen();

        return view('dosen.application-scores.edit', compact('applicationScore', 'dosen'));
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
