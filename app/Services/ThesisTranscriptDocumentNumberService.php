<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ThesisTranscriptNumberSequence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThesisTranscriptDocumentNumberService
{
    /**
     * Format: PSI/TA/{nomor urut per tahun}/{bulan}/{2 digit akhir tahun}
     * Contoh: PSI/TA/001/06/25
     */
    public function format(int $sequence, Carbon $date): string
    {
        return sprintf(
            'PSI/TA/%03d/%s/%s',
            $sequence,
            $date->format('m'),
            $date->format('y')
        );
    }

    /**
     * Tetapkan nomor dokumen rekap nilai pada aplikasi sidang (idempotent jika sudah ada).
     */
    public function assign(Application $application, ?Carbon $issuedAt = null): string
    {
        if ($application->transcript_document_number) {
            return $application->transcript_document_number;
        }

        $issuedAt ??= now();

        return DB::transaction(function () use ($application, $issuedAt) {
            $locked = Application::whereKey($application->id)
                ->lockForUpdate()
                ->first();

            if ($locked?->transcript_document_number) {
                return $locked->transcript_document_number;
            }

            $year = (int) $issuedAt->year;

            ThesisTranscriptNumberSequence::firstOrCreate(
                ['year' => $year],
                ['last_number' => 0]
            );

            $sequenceRow = ThesisTranscriptNumberSequence::where('year', $year)
                ->lockForUpdate()
                ->firstOrFail();

            $sequenceRow->increment('last_number');
            $documentNumber = $this->format($sequenceRow->last_number, $issuedAt);

            $locked->update(['transcript_document_number' => $documentNumber]);

            return $documentNumber;
        });
    }

    public function resolve(Application $application): ?string
    {
        return $application->transcript_document_number ?: null;
    }
}
