<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Dosen;
use App\Models\MbkmRegistration;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MbkmRisetRekapExportService
{
    private const HEADERS = [
        'no ',
        'Dosen Pembimbing',
        'Judul Program',
        'No',
        'Nama Mahasiswa',
        'NIM',
        'Judul Skripsi',
        'SDGs',
        'Pereviu 1',
        'Pereviu 2',
        'Lokasi KKN',
    ];

    public function download(): StreamedResponse
    {
        $rows = $this->buildRows();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('TRACKING REVIU MBKM');
        $sheet->fromArray([self::HEADERS], null, 'A1');

        if ($rows !== []) {
            $sheet->fromArray($rows, null, 'A2');
        }

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Rekap akhir mbkm riset-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function buildRows(): array
    {
        $registrations = MbkmRegistration::withoutGlobalScopes()
            ->whereHas('application', function ($query) {
                $query->where('type', 'mbkm');
            })
            ->with([
                'application.mahasiswa',
                'application.mbkmSeminar.reviewer1',
                'application.mbkmSeminar.reviewer2',
                'preference_supervision',
                'groupMembers.mahasiswa',
            ])
            ->get()
            ->sortBy([
                fn (MbkmRegistration $registration) => $this->resolveSupervisorName($registration),
                fn (MbkmRegistration $registration) => $registration->title_mbkm ?? '',
                fn (MbkmRegistration $registration) => $registration->id,
            ])
            ->values();

        $sdgsByMahasiswaId = $this->loadSdgsByMahasiswaId($registrations);

        $exportRows = [];
        $globalNo = 1;

        foreach ($registrations as $registration) {
            $members = $registration->groupMembers
                ->sortBy(fn ($member) => $member->role === 'ketua' ? 0 : 1)
                ->values();

            if ($members->isEmpty()) {
                $members = collect([
                    (object) [
                        'mahasiswa' => $registration->application?->mahasiswa,
                        'title' => $registration->title,
                    ],
                ]);
            }

            $supervisorName = $this->resolveSupervisorName($registration);
            $programTitle = $registration->title_mbkm;
            $reviewer1 = $registration->application?->mbkmSeminar?->reviewer1?->nama ?? '';
            $reviewer2 = $registration->application?->mbkmSeminar?->reviewer2?->nama ?? '';
            $memberNo = 1;

            foreach ($members as $member) {
                $mahasiswa = $member->mahasiswa ?? null;

                $exportRows[] = [
                    $globalNo,
                    $supervisorName,
                    $memberNo === 1 ? ($programTitle ?? '') : null,
                    $memberNo,
                    $mahasiswa?->nama ?? '',
                    $mahasiswa?->nim ?? '',
                    $member->title ?? '',
                    $mahasiswa?->id ? ($sdgsByMahasiswaId[$mahasiswa->id] ?? '') : '',
                    $reviewer1,
                    $reviewer2,
                    $memberNo === 1 ? ($registration->lokasi_kkn ?? '') : null,
                ];

                $globalNo++;
                $memberNo++;
            }
        }

        return $exportRows;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, MbkmRegistration>  $registrations
     * @return array<int, string>
     */
    private function loadSdgsByMahasiswaId($registrations): array
    {
        $mahasiswaIds = $registrations->flatMap(function (MbkmRegistration $registration) {
            $members = $registration->groupMembers;

            if ($members->isEmpty()) {
                $mahasiswaId = $registration->application?->mahasiswa_id;

                return $mahasiswaId ? collect([$mahasiswaId]) : collect();
            }

            return $members->pluck('mahasiswa_id');
        })->filter()->unique()->values();

        if ($mahasiswaIds->isEmpty()) {
            return [];
        }

        return Application::withoutGlobalScopes()
            ->where('type', 'mbkm')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->with('skripsiDefense')
            ->orderByDesc('id')
            ->get()
            ->unique('mahasiswa_id')
            ->mapWithKeys(fn (Application $application) => [
                $application->mahasiswa_id => $application->skripsiDefense?->sdgsExportText() ?? '',
            ])
            ->all();
    }

    private function resolveSupervisorName(MbkmRegistration $registration): string
    {
        $supervisorId = $registration->application?->resolveSupervisorLecturerId()
            ?? $registration->preference_supervision_id;

        if ($supervisorId) {
            $supervisor = $registration->relationLoaded('preference_supervision')
                && (int) $registration->preference_supervision_id === (int) $supervisorId
                ? $registration->preference_supervision
                : Dosen::find($supervisorId);

            if ($supervisor?->nama) {
                return $supervisor->nama;
            }
        }

        return $registration->preference_supervision?->nama ?? '-';
    }
}
