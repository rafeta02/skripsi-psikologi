<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ApplicationScore;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationScoreController extends Controller
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

    private function authorizeScoreOwnership(ApplicationScore $applicationScore): void
    {
        $dosen = $this->resolveDosen();

        if ((int) $applicationScore->examiner_id !== (int) $dosen->id) {
            abort(403, 'Unauthorized');
        }

        $resultDefense = $applicationScore->application_result_defence;

        if (!$resultDefense || !$resultDefense->isValidatedByAdmin()) {
            abort(403, 'Penilaian belum tersedia. Tunggu validasi admin atas laporan hasil sidang.');
        }

        if ($resultDefense->result === 'failed') {
            abort(403, 'Penilaian tidak diperlukan untuk hasil sidang tidak lulus.');
        }
    }

    public function index()
    {
        $dosen = $this->resolveDosen();

        $scores = ApplicationScore::with([
            'application_result_defence.application.mahasiswa.prodi',
            'application_result_defence.application.skripsiDefense',
        ])
            ->where('examiner_id', $dosen->id)
            ->whereHas('application_result_defence', function ($q) {
                $q->whereIn('result', ['passed', 'revision']);
            })
            ->orderByRaw('CASE WHEN score IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('updated_at')
            ->get();

        $pendingCount = $scores->filter(fn ($s) => !$s->isComplete())->count();

        return view('dosen.scores', compact('scores', 'dosen', 'pendingCount'));
    }

    public function edit(ApplicationScore $applicationScore)
    {
        $this->authorizeScoreOwnership($applicationScore);

        $applicationScore->load([
            'application_result_defence.application.mahasiswa.prodi',
            'application_result_defence.application.skripsiDefense',
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
