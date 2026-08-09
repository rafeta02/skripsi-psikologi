<?php

namespace App\Services;

use App\Models\ApplicationResultDefense;
use App\Models\Dosen;
use App\Models\ThesisTitleEntry;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ThesisTitleDatabaseService
{
    public function getAllEntries(): Collection
    {
        $fromDefense = ApplicationResultDefense::with([
            'application.mahasiswa',
            'application.skripsiDefense.examiner1.dosen',
            'application.skripsiDefense.examiner2.dosen',
            'application.schedules',
        ])
            ->whereNotNull('final_title')
            ->where('final_title', '!=', '')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ApplicationResultDefense $result) => $this->makeDefenseEntry($result));

        $manual = ThesisTitleEntry::orderByDesc('created_at')
            ->get()
            ->map(fn (ThesisTitleEntry $entry) => $this->makeManualEntry($entry));

        return $fromDefense->merge($manual)
            ->sortByDesc(fn (array $entry) => $entry['tanggal_sidang_sort'] ?? $entry['angkatan'] ?? '')
            ->values();
    }

    public function createManualEntry(array $data, int $userId): ThesisTitleEntry
    {
        return ThesisTitleEntry::create([
            'nama' => $data['nama'] ?? null,
            'nim' => $data['nim'] ?? null,
            'angkatan' => $data['angkatan'] ?? null,
            'pembimbing' => $data['pembimbing'] ?? null,
            'title' => trim($data['title']),
            'title_en' => ! empty($data['title_en']) ? trim($data['title_en']) : null,
            'penguji_1' => $data['penguji_1'] ?? null,
            'penguji_2' => $data['penguji_2'] ?? null,
            'tanggal_sidang' => $this->parseDate($data['tanggal_sidang'] ?? null),
            'source' => 'manual',
            'created_by_id' => $userId,
        ]);
    }

    public function importFromCsv(UploadedFile $file, int $userId): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \RuntimeException('Tidak dapat membaca file CSV.');
        }

        $headerRow = fgetcsv($handle);
        if (! $headerRow) {
            fclose($handle);
            throw new \RuntimeException('File CSV kosong.');
        }

        $columnMap = $this->mapCsvHeaders($headerRow);
        if (! isset($columnMap['title'])) {
            fclose($handle);
            throw new \RuntimeException('Kolom judul tidak ditemukan. Gunakan header: judul_skripsi_id atau judul.');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if ($this->isEmptyCsvRow($row)) {
                continue;
            }

            $title = trim($this->csvValue($row, $columnMap, 'title') ?? '');
            if ($title === '') {
                $skipped++;
                $errors[] = "Baris {$line}: judul kosong, dilewati.";

                continue;
            }

            try {
                ThesisTitleEntry::create([
                    'nama' => $this->csvValue($row, $columnMap, 'nama'),
                    'nim' => $this->csvValue($row, $columnMap, 'nim'),
                    'angkatan' => $this->csvValue($row, $columnMap, 'angkatan'),
                    'pembimbing' => $this->csvValue($row, $columnMap, 'pembimbing'),
                    'title' => $title,
                    'title_en' => $this->csvValue($row, $columnMap, 'title_en'),
                    'penguji_1' => $this->csvValue($row, $columnMap, 'penguji_1'),
                    'penguji_2' => $this->csvValue($row, $columnMap, 'penguji_2'),
                    'tanggal_sidang' => $this->parseDate($this->csvValue($row, $columnMap, 'tanggal_sidang')),
                    'source' => 'import',
                    'created_by_id' => $userId,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $skipped++;
                $errors[] = "Baris {$line}: ".$e->getMessage();
            }
        }

        fclose($handle);

        return compact('imported', 'skipped', 'errors');
    }

    public function getCsvTemplateContent(): string
    {
        $headers = [
            'nama',
            'nim',
            'angkatan',
            'pembimbing',
            'judul_skripsi_id',
            'judul_skripsi_en',
            'penguji_1',
            'penguji_2',
            'tanggal_sidang',
        ];
        $example = [
            'Budi Santoso',
            'A123456789',
            '2020',
            'Dr. Ani, M.Psi.',
            'Pengaruh Media Sosial terhadap Kecemasan Remaja',
            'The Influence of Social Media on Adolescent Anxiety',
            'Dr. Citra, M.Psi.',
            'Dr. Dedi, M.Psi.',
            '2024-06-15',
        ];

        $lines = [implode(',', $headers), implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', $v).'"', $example))];

        return implode("\n", $lines);
    }

    public function filterByKeywords(Collection $entries, string $query): Collection
    {
        $keywords = $this->extractKeywords($query);
        if (empty($keywords)) {
            return $entries;
        }

        return $entries->filter(function (array $entry) use ($keywords) {
            $haystack = $this->normalizeTitle(
                ($entry['title'] ?? '').' '.
                ($entry['title_en'] ?? '').' '.
                ($entry['nama'] ?? '').' '.
                ($entry['nim'] ?? '').' '.
                ($entry['pembimbing'] ?? '').' '.
                ($entry['penguji_1'] ?? '').' '.
                ($entry['penguji_2'] ?? '')
            );

            foreach ($keywords as $keyword) {
                if (! str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    public function markDuplicates(Collection $entries): Collection
    {
        $groupsById = $entries->groupBy('normalized');
        $groupsByEn = $entries
            ->filter(fn ($e) => ! empty($e['normalized_en']))
            ->groupBy('normalized_en');

        return $entries->map(function (array $entry) use ($groupsById, $groupsByEn) {
            $idMatches = $groupsById->get($entry['normalized'], collect())
                ->filter(fn ($item) => $item['id'] !== $entry['id']);
            $enMatches = ! empty($entry['normalized_en'])
                ? $groupsByEn->get($entry['normalized_en'], collect())
                    ->filter(fn ($item) => $item['id'] !== $entry['id'])
                : collect();

            $others = $idMatches->merge($enMatches)->unique('id');

            $entry['duplicate_count'] = $others->count();
            $entry['is_duplicate'] = $others->count() > 0;
            $entry['duplicate_with'] = $others->take(3)->pluck('nama')->unique()->values()->all();
            $entry['duplicate_reason'] = $idMatches->isNotEmpty() && $enMatches->isNotEmpty()
                ? 'Judul ID & EN sama'
                : ($enMatches->isNotEmpty() ? 'Judul EN sama' : ($idMatches->isNotEmpty() ? 'Judul ID sama' : null));

            return $entry;
        });
    }

    public function getSummary(Collection $entries): array
    {
        return [
            'total' => $entries->count(),
            'from_sidang' => $entries->where('is_manual', false)->count(),
            'manual' => $entries->where('is_manual', true)->count(),
            'unique_titles' => $entries->pluck('normalized')->unique()->count(),
            'with_english' => $entries->filter(fn ($e) => ! empty($e['title_en']))->count(),
            'duplicate_entries' => $entries->where('is_duplicate', true)->count(),
        ];
    }

    public function normalizeTitle(?string $title): string
    {
        if (! $title) {
            return '';
        }

        $normalized = mb_strtolower($title);
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', trim($normalized));

        return $normalized;
    }

    private function makeDefenseEntry(ApplicationResultDefense $result): array
    {
        $application = $result->application;
        $mahasiswa = $application?->mahasiswa;
        $title = trim($result->final_title ?? '');
        $titleEn = $result->final_title_en ? trim($result->final_title_en) : null;

        $supervisorId = $application?->resolveSupervisorLecturerId();
        $pembimbing = $supervisorId ? (Dosen::find($supervisorId)?->nama) : null;

        $defense = $application?->skripsiDefense;
        $penguji1 = $defense?->examiner1?->dosen?->nama;
        $penguji2 = $defense?->examiner2?->dosen?->nama;

        $schedule = $application?->schedules
            ?->filter(fn ($item) => in_array($item->schedule_type, ['defense', 'skripsi_defense'], true))
            ->sortByDesc(fn ($item) => $item->getRawOriginal('waktu') ?? '')
            ->first();

        $tanggalSidang = $this->parseDate($schedule?->getRawOriginal('waktu'));

        return $this->buildEntryArray([
            'id' => 'defense-'.$result->id,
            'defense_id' => $result->id,
            'application_id' => $result->application_id,
            'nama' => $mahasiswa?->nama ?? '-',
            'nim' => $mahasiswa?->nim ?? '-',
            'angkatan' => $mahasiswa?->tahun_masuk ?: '-',
            'pembimbing' => $pembimbing ?: '-',
            'source' => 'Hasil Sidang',
            'title' => $title,
            'title_en' => $titleEn,
            'penguji_1' => $penguji1 ?: '-',
            'penguji_2' => $penguji2 ?: '-',
            'tanggal_sidang' => $tanggalSidang ? Carbon::parse($tanggalSidang)->format('d M Y') : ($result->created_at?->format('d M Y') ?? '-'),
            'tanggal_sidang_sort' => $tanggalSidang ?: $result->created_at?->format('Y-m-d'),
            'result' => ApplicationResultDefense::RESULT_SELECT[$result->result] ?? $result->result,
            'is_manual' => false,
            'manual_id' => null,
        ]);
    }

    private function makeManualEntry(ThesisTitleEntry $entry): array
    {
        $title = trim($entry->title);
        $titleEn = $entry->title_en ? trim($entry->title_en) : null;

        return $this->buildEntryArray([
            'id' => 'manual-'.$entry->id,
            'manual_id' => $entry->id,
            'application_id' => null,
            'nama' => $entry->nama ?: '-',
            'nim' => $entry->nim ?: '-',
            'angkatan' => $entry->angkatan ?: '-',
            'pembimbing' => $entry->pembimbing ?: '-',
            'source' => $entry->source === 'import' ? 'Import CSV' : 'Input Manual',
            'title' => $title,
            'title_en' => $titleEn,
            'penguji_1' => $entry->penguji_1 ?: '-',
            'penguji_2' => $entry->penguji_2 ?: '-',
            'tanggal_sidang' => $entry->tanggalSidangLabel(),
            'tanggal_sidang_sort' => $entry->tanggal_sidang?->format('Y-m-d') ?? $entry->created_at?->format('Y-m-d'),
            'result' => '-',
            'is_manual' => true,
        ]);
    }

    private function buildEntryArray(array $data): array
    {
        $data['normalized'] = $this->normalizeTitle($data['title']);
        $data['normalized_en'] = ! empty($data['title_en']) ? $this->normalizeTitle($data['title_en']) : '';

        return $data;
    }

    private function parseDate(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function extractKeywords(string $query): array
    {
        $normalized = $this->normalizeTitle($query);

        return array_values(array_filter(
            explode(' ', $normalized),
            fn ($word) => mb_strlen($word) >= 2
        ));
    }

    private function mapCsvHeaders(array $headerRow): array
    {
        $aliases = [
            'title' => ['judul_skripsi_id', 'judul', 'title', 'final_title', 'judul_id', 'judul_indonesia'],
            'title_en' => ['judul_skripsi_en', 'judul_en', 'title_en', 'final_title_en', 'judul_english', 'judul_inggris'],
            'nama' => ['nama', 'mahasiswa', 'nama_mahasiswa', 'student', 'student_name', 'mahasiswa_nama'],
            'nim' => ['nim', 'no_induk'],
            'angkatan' => ['angkatan', 'tahun_masuk', 'tahun', 'year', 'cohort'],
            'pembimbing' => ['pembimbing', 'dosen_pembimbing', 'supervisor'],
            'penguji_1' => ['penguji_1', 'penguji1', 'examiner_1', 'examiner1'],
            'penguji_2' => ['penguji_2', 'penguji2', 'examiner_2', 'examiner2'],
            'tanggal_sidang' => ['tanggal_sidang', 'tgl_sidang', 'defense_date', 'tanggal'],
        ];

        $map = [];
        foreach ($headerRow as $index => $header) {
            $key = $this->normalizeTitle($header);
            $key = str_replace(' ', '_', $key);

            foreach ($aliases as $field => $names) {
                if (in_array($key, $names, true)) {
                    $map[$field] = $index;
                }
            }
        }

        return $map;
    }

    private function csvValue(array $row, array $columnMap, string $field): ?string
    {
        if (! isset($columnMap[$field]) || ! isset($row[$columnMap[$field]])) {
            return null;
        }

        $value = trim($row[$columnMap[$field]]);

        return $value !== '' ? $value : null;
    }

    private function isEmptyCsvRow(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }
}
