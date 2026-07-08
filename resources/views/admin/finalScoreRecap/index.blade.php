@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-chart-pie"></i> Rekap Aspek Nilai Akhir</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Rekap Nilai Akhir</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-user-graduate"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa</span>
                        <span class="info-box-number">{{ $summary['total'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Rata-rata Nilai Akhir</span>
                        <span class="info-box-number">{{ number_format($summary['avg_final_score'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-certificate"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Difinalisasi</span>
                        <span class="info-box-number">{{ $summary['finalized_count'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box mb-0">
                    <span class="info-box-icon bg-warning"><i class="fas fa-layer-group"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Komponen Penilaian</span>
                        <span class="info-box-number">{{ count($componentKeys) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($summary['grade_distribution']->isNotEmpty())
        <div class="card card-outline card-secondary mb-3">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold mb-0">Distribusi Nilai Huruf</h3>
            </div>
            <div class="card-body py-2">
                @foreach($summary['grade_distribution']->sortKeys() as $grade => $count)
                    <span class="badge badge-primary badge-lg mr-2 mb-1">{{ $grade }}: {{ $count }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="card card-outline card-primary mb-3">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Rata-rata per Komponen (Seluruh Mahasiswa)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                @foreach($componentLabels as $key => $label)
                                    <th class="text-center" style="min-width: 110px;">
                                        <small title="{{ $label }}">{{ \Illuminate\Support\Str::limit($label, 28) }}</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($componentKeys as $key)
                                    <td class="text-center font-weight-bold">
                                        @if($summary['component_means'][$key] !== null)
                                            {{ number_format($summary['component_means'][$key], 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Breakdown Nilai per Mahasiswa</h3>
                <div class="card-tools">
                    <form method="GET" class="form-inline">
                        <label class="mr-2 mb-0">Filter:</label>
                        <select name="filter" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="finalized" {{ $filter === 'finalized' ? 'selected' : '' }}>Sudah difinalisasi</option>
                            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua yang sudah dinilai</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Nilai per komponen = rata-rata dari semua dosen penilai.
                    Nilai akhir = rata-rata nilai total tiap dosen penilai.
                </div>

                @if($recap->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Belum ada data nilai akhir.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm" id="rekapNilaiTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Mahasiswa</th>
                                    <th rowspan="2">NIM</th>
                                    <th rowspan="2">Prodi</th>
                                    <th rowspan="2">Jalur</th>
                                    <th rowspan="2">Hasil</th>
                                    <th rowspan="2" class="text-center bg-success">Nilai Akhir</th>
                                    <th rowspan="2" class="text-center">Huruf</th>
                                    <th colspan="{{ count($componentKeys) }}" class="text-center bg-info">Rata-rata per Komponen</th>
                                    <th rowspan="2" class="text-center">Penilai</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    @foreach($componentLabels as $key => $label)
                                        <th class="text-center" style="font-size: 0.7rem; min-width: 72px;" title="{{ $label }}">
                                            {{ \Illuminate\Support\Str::limit($label, 12) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recap as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><strong>{{ $row['mahasiswa'] }}</strong></td>
                                        <td>{{ $row['nim'] }}</td>
                                        <td>{{ $row['prodi'] }}</td>
                                        <td><span class="badge badge-primary">{{ $row['type'] }}</span></td>
                                        <td>
                                            @php
                                                $resultBadge = match($row['result']) {
                                                    'passed' => 'success',
                                                    'revision' => 'warning',
                                                    'failed' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $resultBadge }}">{{ $row['result_label'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-success">{{ number_format($row['final_score'], 2) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-dark badge-lg">{{ $row['grade'] }}</span>
                                        </td>
                                        @foreach($componentKeys as $key)
                                            <td class="text-center">
                                                @if($row['component_averages'][$key] !== null)
                                                    {{ number_format($row['component_averages'][$key], 1) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center">{{ $row['scorer_count'] }}</td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-primary btn-detail-nilai"
                                                    data-url="{{ route('admin.final-score-recap.detail', $row['defense_id']) }}"
                                                    data-nama="{{ $row['mahasiswa'] }}">
                                                <i class="fas fa-search"></i> Detail
                                            </button>
                                            @if($row['application_id'])
                                                <a href="{{ route('admin.application-result-defenses.show', $row['defense_id']) }}"
                                                   class="btn btn-sm btn-outline-secondary" title="Hasil Sidang">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-chart-bar"></i> Detail Nilai — <span id="detailModalNama"></span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    if ($.fn.DataTable && $('#rekapNilaiTable').length) {
        $('#rekapNilaiTable').DataTable({
            order: [[6, 'desc']],
            pageLength: 25,
            scrollX: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });
    }

    $('.btn-detail-nilai').on('click', function () {
        var url = $(this).data('url');
        var nama = $(this).data('nama');
        $('#detailModalNama').text(nama);
        $('#detailModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>');
        $('#detailModal').modal('show');
        $.get(url, function (html) {
            $('#detailModalBody').html(html);
        }).fail(function () {
            $('#detailModalBody').html('<div class="alert alert-danger">Gagal memuat data.</div>');
        });
    });
});
</script>
@endsection
