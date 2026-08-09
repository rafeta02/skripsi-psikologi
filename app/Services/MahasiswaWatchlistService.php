<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationResultReview;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Mahasiswa yang sudah divalidasi admin (laporan hasil review Reguler)
 * tetapi belum mendaftar sidang skripsi melewati batas waktu.
 */
class MahasiswaWatchlistService
{
    public function graceDays(): int
    {
        return (int) config('thesis.defense_registration_grace_days', 30);
    }

    public function countRegulerWatchlist(): int
    {
        return $this->getRegulerWatchlistEntries()->count();
    }

    /**
     * @return Collection<int, object{
     *     result_review_id: int,
     *     application_id: int,
     *     mahasiswa_id: int,
     *     mahasiswa_name: string,
     *     mahasiswa_nim: string,
     *     pembimbing_name: ?string,
     *     validated_at: Carbon,
     *     validated_at_label: string,
     *     idle_days: int,
     *     result_label: string,
     *     wa_url: ?string,
     *     detail_url: string,
     * }>
     */
    public function getRegulerWatchlistEntries(): Collection
    {
        $threshold = now()->subDays($this->graceDays());
        $supervisorNames = [];

        $reviews = ApplicationResultReview::query()
            ->with([
                'application.mahasiswa.user',
                'application.skripsiSeminar',
                'application.actions',
            ])
            ->whereHas('application', function ($query) {
                $query->where('type', 'skripsi')->where('stage', 'seminar');
            })
            ->whereIn('result', [
                'approved_no_revision',
                'approved_minor_revision',
                'approved_major_revision',
                'passed',
            ])
            ->whereHas('application.actions', function ($query) {
                $query->where('action_type', 'result_review_approved');
            })
            ->orderByDesc('created_at')
            ->get();

        $formAccess = app(FormAccessService::class);

        return $reviews
            ->map(function (ApplicationResultReview $review) use ($threshold, $formAccess, &$supervisorNames) {
                $application = $review->application;
                $mahasiswa = $application?->mahasiswa;

                if (! $application || ! $mahasiswa) {
                    return null;
                }

                $validatedAt = $application->actions
                    ->where('action_type', 'result_review_approved')
                    ->sortByDesc('created_at')
                    ->first()
                    ?->created_at;

                if (! $validatedAt instanceof Carbon) {
                    $validatedAt = $validatedAt ? Carbon::parse($validatedAt) : null;
                }

                if (! $validatedAt || $validatedAt->gt($threshold)) {
                    return null;
                }

                if ($this->hasActiveDefenseRegistration($mahasiswa->id, $formAccess)) {
                    return null;
                }

                $supervisorId = $application->resolveSupervisorLecturerId();
                if ($supervisorId) {
                    if (! array_key_exists($supervisorId, $supervisorNames)) {
                        $supervisorNames[$supervisorId] = Dosen::find($supervisorId)?->nama;
                    }
                }

                $idleDays = $validatedAt->diffInDays(now());

                return (object) [
                    'result_review_id' => $review->id,
                    'application_id' => $application->id,
                    'mahasiswa_id' => $mahasiswa->id,
                    'mahasiswa_name' => $mahasiswa->nama,
                    'mahasiswa_nim' => $mahasiswa->nim,
                    'pembimbing_name' => $supervisorId ? ($supervisorNames[$supervisorId] ?? null) : null,
                    'validated_at' => $validatedAt,
                    'validated_at_label' => $validatedAt->format('d M Y'),
                    'idle_days' => $idleDays,
                    'result_label' => $review->resultLabel(),
                    'wa_url' => $this->whatsappReminderUrl($mahasiswa, $review, $validatedAt, $idleDays),
                    'detail_url' => route('admin.application-result-reviews.show', $review->id),
                ];
            })
            ->filter()
            ->sortByDesc('idle_days')
            ->values();
    }

    public function hasActiveDefenseRegistration(int $mahasiswaId, ?FormAccessService $formAccess = null): bool
    {
        $formAccess ??= app(FormAccessService::class);

        $defenseApps = Application::query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'skripsi')
            ->where('stage', 'defense')
            ->whereIn('status', ['submitted', 'approved', 'scheduled', 'result', 'revision'])
            ->get();

        foreach ($defenseApps as $defenseApp) {
            if (! $formAccess->defenseCycleClosedByValidatedFailure($defenseApp->id)) {
                return true;
            }
        }

        return false;
    }

    public function whatsappReminderUrl(
        $mahasiswa,
        ApplicationResultReview $review,
        Carbon $validatedAt,
        int $idleDays
    ): ?string {
        $phone = $mahasiswa->user?->whatsappNumberForLink();
        if (! $phone) {
            return null;
        }

        $nama = $mahasiswa->nama ?? 'Mahasiswa';
        $nim = $mahasiswa->nim ?? '-';
        $judul = $review->application?->skripsiSeminar?->title ?? '-';
        $validatedLabel = $validatedAt->format('d M Y');
        $graceDays = $this->graceDays();

        $message = "Yth. {$nama},\n\n"
            ."Laporan Hasil Review Kelayakan Proposal (Reguler) Anda telah divalidasi admin sejak {$validatedLabel} "
            ."(lebih dari {$graceDays} hari / {$idleDays} hari), "
            ."namun kami belum menerima pendaftaran Sidang Skripsi Anda.\n\n"
            ."Detail:\n"
            ."- NIM: {$nim}\n"
            ."- Judul: {$judul}\n"
            ."- Hasil review: {$review->resultLabel()}\n\n"
            ."Mohon segera login ke SIMSKRIPSI untuk melakukan pendaftaran sidang skripsi.\n\n"
            .'Terima kasih.';

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
