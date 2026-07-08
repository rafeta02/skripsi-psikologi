<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Services\DosenWorkloadService;

class DosenWorkloadController extends Controller
{
    public function __construct(private DosenWorkloadService $workloadService)
    {
    }

    public function rekapPembimbing()
    {
        $recap = $this->workloadService->getPembimbingRecap();

        $summary = [
            'total_dosen' => $recap->count(),
            'total_aktif' => $recap->sum('aktif'),
            'total_menunggu' => $recap->sum('menunggu'),
            'max_beban' => $recap->max('aktif') ?? 0,
        ];

        return view('admin.dosenWorkload.rekap-pembimbing', compact('recap', 'summary'));
    }

    public function rekapPenguji()
    {
        $recap = $this->workloadService->getPengujiRecap();

        $summary = [
            'total_dosen' => $recap->count(),
            'total_penugasan' => $recap->sum('total'),
            'total_aktif' => $recap->sum('aktif'),
            'max_beban' => $recap->max('total') ?? 0,
        ];

        return view('admin.dosenWorkload.rekap-penguji', compact('recap', 'summary'));
    }

    public function pembimbingDetail(Dosen $dosen)
    {
        $items = $this->workloadService->getPembimbingDetail($dosen->id);

        return view('admin.dosenWorkload.partials.pembimbing-detail', compact('dosen', 'items'));
    }

    public function pengujiDetail(Dosen $dosen)
    {
        $items = $this->workloadService->getPengujiDetail($dosen->id);

        return view('admin.dosenWorkload.partials.penguji-detail', compact('dosen', 'items'));
    }
}
