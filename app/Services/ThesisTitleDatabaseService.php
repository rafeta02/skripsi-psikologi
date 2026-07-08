<?php

namespace App\Services;

use App\Models\ApplicationResultDefense;
use App\Models\ThesisTitleEntry;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ThesisTitleDatabaseService
{
    public function getAllEntries(): Collection
    {
        $fromDefense = ApplicationResultDefense::with(['application.mahasiswa.prodi'])
            ->whereNotNull('final_title')
            ->where('final_title', '!=', '')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ApplicationResultDefense $result) => $this->makeDefenseEntry($result));

        $manual = ThesisTitleEntry::orderByDesc('created_at')
            ->get()
            ->map(fn (ThesisTitleEntry $entry) => $this->makeManualEntry($entry));

        return $fromDefense->merge($manual)
            ->sortByDesc('year')
            ->values();
    }

    public function createManualEntry(array $data, int $userId): ThesisTitleEntry
    {
        return ThesisTitleEntry::create([
            'mahasiswa_nama' => $data['mahasiswa_nama'] ?? null,
            'nim' => $data['nim'] ?? null,
            'prodi' => $data['prodi'] ?? null,
            'type' => $data['type'] ?? null,
            'title' => trim($data['title']),
            'title_en' => !empty($data['title_en']) ? trim($data['title_en']) : null,
            'year' => $data['year'] ?? null,
            'note' => $data['note'] ?? null,
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
        if (!$headerRow) {
            fclose($handle);
            throw new \RuntimeException('File CSV kosong.');
        }

        $columnMap = $this->mapCsvHeaders($headerRow);
        if (!isset($columnMap['title'])) {
            fclose($handle);
            throw new \RuntimeException('Kolom judul tidak ditemukan. Gunakan header: judul atau title.');
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
                    'mahasiswa_nama' => $this->csvValue($row, $columnMap, 'mahasiswa_nama'),
                    'nim' => $this->csvValue($row, $columnMap, 'nim'),
                    'prodi' => $this->csvValue($row, $columnMap, 'prodi'),
                    'type' => $this->normalizeType($this->csvValue($row, $columnMap, 'type')),
                    'title' => $title,
                    'title_en' => $this->csvValue($row, $columnMap, 'title_en'),
                    'year' => $this->csvValue($row, $columnMap, 'year'),
                    'note' => $this->csvValue($row, $columnMap, 'note'),
                    'source' => 'import',
                    'created_by_id' => $userId,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $skipped++;
                $errors[] = "Baris {$line}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return compact('imported', 'skipped', 'errors');
    }

    public function getCsvTemplateContent(): string
    {
        $headers = ['judul', 'judul_en', 'mahasiswa', 'nim', 'prodi', 'jalur', 'tahun', 'catatan'];
        $example = [
            'Pengaruh Media Sosial terhadap Kecemasan Remaja',
            'The Influence of Social Media on Adolescent Anxiety',
            'Budi Santoso',
            'A123456789',
            'Psikologi',
            'skripsi',
            '2024',
            'Data arsip lama',
        ];

        $lines = [implode(',', $headers), implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', $v) . '"', $example))];

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
                ($entry['title'] ?? '') . ' ' .
                ($entry['title_en'] ?? '') . ' ' .
                ($entry['mahasiswa'] ?? '') . ' ' .
                ($entry['nim'] ?? '')
            );

            foreach ($keywords as $keyword) {
                if (!str_contains($haystack, $keyword)) {
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
            ->filter(fn ($e) => !empty($e['normalized_en']))
            ->groupBy('normalized_en');

        return $entries->map(function (array $entry) use ($groupsById, $groupsByEn) {
            $idMatches = $groupsById->get($entry['normalized'], collect())
                ->filter(fn ($item) => $item['id'] !== $entry['id']);
            $enMatches = !empty($entry['normalized_en'])
                ? $groupsByEn->get($entry['normalized_en'], collect())
                    ->filter(fn ($item) => $item['id'] !== $entry['id'])
                : collect();

            $others = $idMatches->merge($enMatches)->unique('id');

            $entry['duplicate_count'] = $others->count();
            $entry['is_duplicate'] = $others->count() > 0;
            $entry['duplicate_with'] = $others->take(3)->pluck('mahasiswa')->unique()->values()->all();
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
            'with_english' => $entries->filter(fn ($e) => !empty($e['title_en']))->count(),
            'duplicate_entries' => $entries->where('is_duplicate', true)->count(),
        ];
    }

    public function normalizeTitle(?string $title): string
    {
        if (!$title) {
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
        $title = trim($result->final_title ?? '');
        $titleEn = $result->final_title_en ? trim($result->final_title_en) : null;

        return $this->buildEntryArray([
            'id' => 'defense-' . $result->id,
            'defense_id' => $result->id,
            'application_id' => $result->application_id,
            'mahasiswa' => $application?->mahasiswa?->nama ?? '-',
            'nim' => $application?->mahasiswa?->nim ?? '-',
            'prodi' => $application?->mahasiswa?->prodi?->name ?? '-',
            'type' => strtoupper($application?->type ?? '-'),
            'source' => 'Hasil Sidang',
            'title' => $title,
            'title_en' => $titleEn,
            'year' => $result->created_at ? $result->created_at->format('Y') : '-',
            'date' => $result->created_at ? $result->created_at->format('d M Y') : '-',
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
            'id' => 'manual-' . $entry->id,
            'manual_id' => $entry->id,
            'application_id' => null,
            'mahasiswa' => $entry->mahasiswa_nama ?: '-',
            'nim' => $entry->nim ?: '-',
            'prodi' => $entry->prodi ?: '-',
            'type' => $entry->type ? strtoupper($entry->type) : '-',
            'source' => $entry->source === 'import' ? 'Import CSV' : 'Input Manual',
            'title' => $title,
            'title_en' => $titleEn,
            'year' => $entry->year ?: ($entry->created_at?->format('Y') ?? '-'),
            'date' => $entry->created_at?->format('d M Y') ?? '-',
            'result' => '-',
            'is_manual' => true,
        ]);
    }

    private function buildEntryArray(array $data): array
    {
        $data['normalized'] = $this->normalizeTitle($data['title']);
        $data['normalized_en'] = !empty($data['title_en']) ? $this->normalizeTitle($data['title_en']) : '';

        return $data;
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
            'title' => ['judul', 'title', 'final_title', 'judul_id', 'judul_indonesia'],
            'title_en' => ['judul_en', 'title_en', 'final_title_en', 'judul_english', 'judul_inggris'],
            'mahasiswa_nama' => ['mahasiswa', 'nama', 'nama_mahasiswa', 'student', 'student_name'],
            'nim' => ['nim', 'no_induk'],
            'prodi' => ['prodi', 'program_studi', 'program studi'],
            'type' => ['jalur', 'type', 'jenis', 'tipe'],
            'year' => ['tahun', 'year', 'angkatan'],
            'note' => ['catatan', 'note', 'keterangan'],
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
        if (!isset($columnMap[$field]) || !isset($row[$columnMap[$field]])) {
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

    private function normalizeType(?string $type): ?string
    {
        if (!$type) {
            return null;
        }

        $type = strtolower(trim($type));

        return in_array($type, ['skripsi', 'mbkm'], true) ? $type : null;
    }
}
