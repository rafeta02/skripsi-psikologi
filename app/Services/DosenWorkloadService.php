<?php

namespace App\Services;

use App\Models\ApplicationAssignment;
use App\Models\Dosen;
use App\Models\MbkmSeminar;
use App\Models\SkripsiDefenseExaminer;
use App\Models\SkripsiSeminar;
use Illuminate\Support\Collection;

class DosenWorkloadService
{
    public function getPembimbingRecap(): Collection
    {
        $rows = ApplicationAssignment::query()
            ->selectRaw('
                lecturer_id,
                COUNT(*) as total,
                SUM(CASE WHEN application_assignments.status = ? THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN application_assignments.status = ? THEN 1 ELSE 0 END) as diterima,
                SUM(CASE WHEN application_assignments.status = ? THEN 1 ELSE 0 END) as ditolak,
                SUM(CASE WHEN application_assignments.status = ? AND applications.status NOT IN (?, ?) THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN application_assignments.status = ? AND applications.status = ? THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN application_assignments.status = ? AND applications.type = ? THEN 1 ELSE 0 END) as reguler,
                SUM(CASE WHEN application_assignments.status = ? AND applications.type = ? THEN 1 ELSE 0 END) as mbkm
            ', [
                'assigned', 'accepted', 'rejected',
                'accepted', 'done', 'rejected',
                'accepted', 'done',
                'accepted', 'skripsi',
                'accepted', 'mbkm',
            ])
            ->join('applications', 'applications.id', '=', 'application_assignments.application_id')
            ->where('application_assignments.role', 'supervisor')
            ->whereNull('application_assignments.deleted_at')
            ->groupBy('lecturer_id')
            ->get()
            ->keyBy('lecturer_id');

        $dosens = Dosen::with('prodi')
            ->whereIn('id', $rows->keys())
            ->orderBy('nama')
            ->get()
            ->keyBy('id');

        return $rows->map(function ($row) use ($dosens) {
            $dosen = $dosens->get($row->lecturer_id);

            return [
                'dosen_id' => (int) $row->lecturer_id,
                'nama' => $dosen->nama ?? '-',
                'nidn' => $dosen->nidn ?? '-',
                'nip' => $dosen->nip ?? '-',
                'prodi' => $dosen->prodi->name ?? '-',
                'total' => (int) $row->total,
                'menunggu' => (int) $row->menunggu,
                'diterima' => (int) $row->diterima,
                'ditolak' => (int) $row->ditolak,
                'aktif' => (int) $row->aktif,
                'selesai' => (int) $row->selesai,
                'reguler' => (int) $row->reguler,
                'mbkm' => (int) $row->mbkm,
            ];
        })
            ->sortByDesc('aktif')
            ->values();
    }

    public function getPembimbingDetail(int $dosenId): Collection
    {
        return ApplicationAssignment::with([
            'application.mahasiswa.prodi',
            'application.skripsiRegistration',
            'application.mbkmRegistration',
        ])
            ->where('lecturer_id', $dosenId)
            ->where('role', 'supervisor')
            ->orderByDesc('assigned_at')
            ->get()
            ->map(function (ApplicationAssignment $assignment) {
                $app = $assignment->application;
                $title = $app?->skripsiRegistration?->title
                    ?? $app?->mbkmRegistration?->title_mbkm
                    ?? $app?->mbkmRegistration?->title
                    ?? '-';

                return [
                    'mahasiswa' => $app?->mahasiswa?->nama ?? '-',
                    'nim' => $app?->mahasiswa?->nim ?? '-',
                    'prodi' => $app?->mahasiswa?->prodi?->name ?? '-',
                    'type' => strtoupper($app?->type ?? '-'),
                    'stage' => ucfirst($app?->stage ?? '-'),
                    'status_penugasan' => $assignment->status,
                    'status_aplikasi' => $app?->status ?? '-',
                    'judul' => $title,
                    'assigned_at' => $assignment->getRawOriginal('assigned_at')
                        ? \Carbon\Carbon::parse($assignment->getRawOriginal('assigned_at'))->format('d M Y')
                        : '-',
                ];
            });
    }

    public function getPengujiRecap(): Collection
    {
        $workload = [];

        $this->accumulateSeminarReviewers(
            SkripsiSeminar::query()->with('application')->whereNotNull('reviewer_1_id')->get(),
            'reviewer_1_id',
            'seminar_reguler',
            $workload
        );
        $this->accumulateSeminarReviewers(
            SkripsiSeminar::query()->with('application')->whereNotNull('reviewer_2_id')->get(),
            'reviewer_2_id',
            'seminar_reguler',
            $workload
        );
        $this->accumulateSeminarReviewers(
            MbkmSeminar::query()->with('application')->whereNotNull('reviewer_1_id')->get(),
            'reviewer_1_id',
            'seminar_mbkm',
            $workload
        );
        $this->accumulateSeminarReviewers(
            MbkmSeminar::query()->with('application')->whereNotNull('reviewer_2_id')->get(),
            'reviewer_2_id',
            'seminar_mbkm',
            $workload
        );

        SkripsiDefenseExaminer::with(['skripsiDefense.application'])
            ->get()
            ->each(function (SkripsiDefenseExaminer $examiner) use (&$workload) {
                $dosenId = (int) $examiner->dosen_id;
                if (!$dosenId) {
                    return;
                }

                $this->initPengujiRow($workload, $dosenId);
                $workload[$dosenId]['sidang']++;
                $workload[$dosenId]['total']++;

                $appStatus = $examiner->skripsiDefense?->application?->status;
                if ($appStatus && !in_array($appStatus, ['done', 'rejected'], true)) {
                    $workload[$dosenId]['aktif']++;
                } else {
                    $workload[$dosenId]['selesai']++;
                }
            });

        if (empty($workload)) {
            return collect();
        }

        $dosens = Dosen::with('prodi')
            ->whereIn('id', array_keys($workload))
            ->orderBy('nama')
            ->get()
            ->keyBy('id');

        return collect($workload)
            ->map(function (array $row, int $dosenId) use ($dosens) {
                $dosen = $dosens->get($dosenId);

                return array_merge($row, [
                    'dosen_id' => $dosenId,
                    'nama' => $dosen->nama ?? '-',
                    'nidn' => $dosen->nidn ?? '-',
                    'nip' => $dosen->nip ?? '-',
                    'prodi' => $dosen->prodi->name ?? '-',
                ]);
            })
            ->sortByDesc('total')
            ->values();
    }

    public function getPengujiDetail(int $dosenId): Collection
    {
        $items = collect();

        SkripsiSeminar::with(['application.mahasiswa.prodi'])
            ->where(function ($q) use ($dosenId) {
                $q->where('reviewer_1_id', $dosenId)->orWhere('reviewer_2_id', $dosenId);
            })
            ->get()
            ->each(function (SkripsiSeminar $seminar) use ($dosenId, $items) {
                $role = (int) $seminar->reviewer_1_id === $dosenId ? 'Reviewer 1' : 'Reviewer 2';
                $items->push($this->formatPengujiItem(
                    'Seminar Reguler',
                    $role,
                    $seminar->application,
                    $seminar->title
                ));
            });

        MbkmSeminar::with(['application.mahasiswa.prodi'])
            ->where(function ($q) use ($dosenId) {
                $q->where('reviewer_1_id', $dosenId)->orWhere('reviewer_2_id', $dosenId);
            })
            ->get()
            ->each(function (MbkmSeminar $seminar) use ($dosenId, $items) {
                $role = (int) $seminar->reviewer_1_id === $dosenId ? 'Reviewer 1' : 'Reviewer 2';
                $items->push($this->formatPengujiItem(
                    'Review Kelayakan Proposal',
                    $role,
                    $seminar->application,
                    $seminar->title ?? null
                ));
            });

        SkripsiDefenseExaminer::with(['skripsiDefense.application.mahasiswa.prodi'])
            ->where('dosen_id', $dosenId)
            ->get()
            ->each(function (SkripsiDefenseExaminer $examiner) use ($items) {
                $defense = $examiner->skripsiDefense;
                $roleLabel = match ($examiner->role) {
                    'examiner_1' => 'Penguji 1',
                    'examiner_2' => 'Penguji 2',
                    default => ucfirst(str_replace('_', ' ', $examiner->role ?? 'Penguji')),
                };

                $items->push($this->formatPengujiItem(
                    'Sidang Skripsi',
                    $roleLabel,
                    $defense?->application,
                    $defense?->title
                ));
            });

        return $items->sortBy('mahasiswa')->values();
    }

    private function accumulateSeminarReviewers($seminars, string $reviewerField, string $bucket, array &$workload): void
    {
        foreach ($seminars as $seminar) {
            $dosenId = (int) $seminar->{$reviewerField};
            if (!$dosenId) {
                continue;
            }

            $this->initPengujiRow($workload, $dosenId);
            $workload[$dosenId][$bucket]++;
            $workload[$dosenId]['total']++;

            $appStatus = $seminar->application?->status;
            if ($appStatus && !in_array($appStatus, ['done', 'rejected'], true)) {
                $workload[$dosenId]['aktif']++;
            } else {
                $workload[$dosenId]['selesai']++;
            }
        }
    }

    private function initPengujiRow(array &$workload, int $dosenId): void
    {
        if (!isset($workload[$dosenId])) {
            $workload[$dosenId] = [
                'seminar_reguler' => 0,
                'seminar_mbkm' => 0,
                'sidang' => 0,
                'total' => 0,
                'aktif' => 0,
                'selesai' => 0,
            ];
        }
    }

    private function formatPengujiItem(string $jenis, string $peran, $application, ?string $judul): array
    {
        return [
            'jenis' => $jenis,
            'peran' => $peran,
            'mahasiswa' => $application?->mahasiswa?->nama ?? '-',
            'nim' => $application?->mahasiswa?->nim ?? '-',
            'prodi' => $application?->mahasiswa?->prodi?->name ?? '-',
            'type' => strtoupper($application?->type ?? '-'),
            'status_aplikasi' => $application?->status ?? '-',
            'judul' => $judul ?? '-',
        ];
    }
}
