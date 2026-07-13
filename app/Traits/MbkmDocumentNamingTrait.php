<?php

namespace App\Traits;

use App\Models\Mahasiswa;
use Illuminate\Support\Str;

/**
 * Nama file upload MBKM: MBKM_{DOC}_{NIM}_{NAMA}[_SEMESTER-n]_{Ymd_His}.{ext}
 */
trait MbkmDocumentNamingTrait
{
    protected function generateFileName($collectionName, ?int $index = null)
    {
        $mahasiswa = $this->resolveMahasiswaForDocumentNaming();
        $nim = $mahasiswa?->nim ?? 'unknown';
        $name = strtoupper(Str::slug($mahasiswa?->nama ?? 'mahasiswa', '_'));
        $timestamp = now()->format('Ymd_His');
        $code = $this->mbkmDocumentCode($collectionName);

        if ($collectionName === 'khs_all') {
            $semester = $index ?? 1;

            return sprintf('MBKM_%s_%s_%s_SEMESTER-%d_%s', $code, $nim, $name, $semester, $timestamp);
        }

        return sprintf('MBKM_%s_%s_%s_%s', $code, $nim, $name, $timestamp);
    }

    protected function mbkmDocumentCode(string $collectionName): string
    {
        return match ($collectionName) {
            'khs_all' => 'KHS',
            'krs_latest' => 'KRS',
            'spp' => 'SPP',
            'proposal_mbkm' => 'PROPOSAL',
            'recognition_form' => 'RECOGNITION',
            default => strtoupper(Str::slug($collectionName, '_')),
        };
    }

    protected function resolveMahasiswaForDocumentNaming(): ?Mahasiswa
    {
        if (method_exists($this, 'mahasiswa') && $this->mahasiswa_id) {
            if ($this->relationLoaded('mahasiswa')) {
                return $this->mahasiswa;
            }

            return $this->mahasiswa()->first();
        }

        if (method_exists($this, 'application') && $this->application_id) {
            $application = $this->relationLoaded('application')
                ? $this->application
                : $this->application()->with('mahasiswa')->first();

            return $application?->mahasiswa;
        }

        return null;
    }
}
