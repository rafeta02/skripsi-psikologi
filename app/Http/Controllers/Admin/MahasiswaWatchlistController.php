<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MahasiswaWatchlistService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaWatchlistController extends Controller
{
    public function index(Request $request, MahasiswaWatchlistService $watchlistService)
    {
        abort_if(Gate::denies('mahasiswa_watchlist_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            if ($request->get('type') === 'defense_result') {
                return $this->defenseResultDataTable($watchlistService);
            }

            return $this->regulerDataTable($watchlistService);
        }

        return view('admin.mahasiswaWatchlists.index', [
            'graceDays' => $watchlistService->graceDays(),
            'defenseResultWarningStartDays' => $watchlistService->defenseResultSubmissionWarningStartDays(),
            'regulerWatchlistCount' => $watchlistService->countRegulerWatchlist(),
            'defenseResultWatchlistCount' => $watchlistService->countDefenseResultWatchlist(),
        ]);
    }

    private function regulerDataTable(MahasiswaWatchlistService $watchlistService)
    {
        $entries = $watchlistService->getRegulerWatchlistEntries();

        $table = DataTables::of($entries);

        $table->addColumn('placeholder', '&nbsp;');

        $table->addColumn('mahasiswa_name', function ($row) {
            $nama = e($row->mahasiswa_name);
            $nim = e($row->mahasiswa_nim);

            return '<div class="font-weight-bold">'.$nama.'</div><small class="text-muted">'.$nim.'</small>';
        });

        $table->addColumn('pembimbing_name', function ($row) {
            return $row->pembimbing_name
                ? e($row->pembimbing_name)
                : '<span class="text-muted">-</span>';
        });

        $table->addColumn('validated_at', function ($row) {
            return e($row->validated_at_label);
        });

        $table->addColumn('idle_days', function ($row) {
            $class = $row->idle_days >= 60 ? 'danger' : ($row->idle_days >= 45 ? 'warning' : 'info');

            return '<span class="badge badge-'.$class.'">'.$row->idle_days.' hari</span>';
        });

        $table->addColumn('result_label', function ($row) {
            return e($row->result_label);
        });

        $table->addColumn('actions', function ($row) {
            $html = '<a href="'.e($row->detail_url).'" class="btn btn-xs btn-primary" title="Detail laporan">'
                .'<i class="fas fa-eye"></i></a>';

            if ($row->wa_url) {
                $html .= ' <a href="'.e($row->wa_url).'" target="_blank" rel="noopener" '
                    .'class="btn btn-xs btn-success" title="Ingatkan via WhatsApp">'
                    .'<i class="fab fa-whatsapp"></i> WA</a>';
            } else {
                $html .= ' <button type="button" class="btn btn-xs btn-secondary" disabled title="Nomor WA tidak tersedia">'
                    .'<i class="fab fa-whatsapp"></i></button>';
            }

            return $html;
        });

        $table->rawColumns([
            'placeholder',
            'mahasiswa_name',
            'pembimbing_name',
            'idle_days',
            'actions',
        ]);

        return $table->make(true);
    }

    private function defenseResultDataTable(MahasiswaWatchlistService $watchlistService)
    {
        $entries = $watchlistService->getDefenseResultWatchlistEntries();

        $table = DataTables::of($entries);

        $table->addColumn('placeholder', '&nbsp;');

        $table->addColumn('mahasiswa_name', function ($row) {
            $nama = e($row->mahasiswa_name);
            $nim = e($row->mahasiswa_nim);

            return '<div class="font-weight-bold">'.$nama.'</div><small class="text-muted">'.$nim.'</small>';
        });

        $table->addColumn('pembimbing_name', function ($row) {
            return $row->pembimbing_name
                ? e($row->pembimbing_name)
                : '<span class="text-muted">-</span>';
        });

        $table->addColumn('jalur_label', function ($row) {
            $class = $row->jalur_label === 'MBKM' ? 'info' : 'primary';

            return '<span class="badge badge-'.$class.'">'.e($row->jalur_label).'</span>';
        });

        $table->addColumn('defense_held_at', function ($row) {
            return e($row->defense_held_at_label);
        });

        $table->addColumn('days_since_defense', function ($row) {
            if ($row->days_since_defense > 30) {
                $class = 'danger';
            } elseif ($row->days_since_defense >= 28) {
                $class = 'warning';
            } else {
                $class = 'info';
            }

            return '<span class="badge badge-'.$class.'">'.$row->days_since_defense.' hari</span>';
        });

        $table->addColumn('status_label', function ($row) {
            return '<span class="badge badge-warning">'.e($row->status_label).'</span>';
        });

        $table->addColumn('actions', function ($row) {
            $html = '<a href="'.e($row->detail_url).'" class="btn btn-xs btn-primary" title="Detail sidang">'
                .'<i class="fas fa-eye"></i></a>';

            if ($row->wa_url) {
                $html .= ' <a href="'.e($row->wa_url).'" target="_blank" rel="noopener" '
                    .'class="btn btn-xs btn-success" title="Ingatkan via WhatsApp">'
                    .'<i class="fab fa-whatsapp"></i> WA</a>';
            } else {
                $html .= ' <button type="button" class="btn btn-xs btn-secondary" disabled title="Nomor WA tidak tersedia">'
                    .'<i class="fab fa-whatsapp"></i></button>';
            }

            return $html;
        });

        $table->rawColumns([
            'placeholder',
            'mahasiswa_name',
            'pembimbing_name',
            'jalur_label',
            'days_since_defense',
            'status_label',
            'actions',
        ]);

        return $table->make(true);
    }
}
