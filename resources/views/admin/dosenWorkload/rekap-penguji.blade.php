@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-gavel"></i> Rekap Beban Penguji</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Rekap Penguji</li>
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
                    <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dosen Penguji</span>
                        <span class="info-box-number">{{ $summary['total_dosen'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-clipboard-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Penugasan</span>
                        <span class="info-box-number">{{ $summary['total_penugasan'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-tasks"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Penugasan Aktif</span>
                        <span class="info-box-number">{{ $summary['total_aktif'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-chart-bar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Beban Tertinggi</span>
                        <span class="info-box-number">{{ $summary['max_beban'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Daftar Beban Penguji per Dosen</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.dosen-workload.rekap-pembimbing') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-user-tie"></i> Lihat Rekap Pembimbing
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i>
                    Beban penguji dihitung dari penugasan <strong>reviewer seminar</strong> (reguler & MBKM) dan <strong>penguji sidang</strong>.
                    Untuk <strong>MBKM kelompok</strong> (review kelayakan proposal), beban dihitung per mahasiswa, bukan per kelompok.
                    Sidang tetap dihitung per individu. Klik <strong>Detail</strong> untuk daftar lengkap.
                </div>

                @if($recap->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Belum ada data penugasan penguji.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="rekapPengujiTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Dosen</th>
                                    <th>NIDN</th>
                                    <th>Prodi</th>
                                    <th class="text-center">Seminar Reguler</th>
                                    <th class="text-center">Review Kelayakan Proposal</th>
                                    <th class="text-center">Sidang</th>
                                    <th class="text-center bg-success text-white">Aktif</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recap as $i => $row)
                                    <tr class="{{ $row['total'] >= 8 ? 'table-warning' : '' }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td><strong>{{ $row['nama'] }}</strong></td>
                                        <td>{{ $row['nidn'] }}</td>
                                        <td>{{ $row['prodi'] }}</td>
                                        <td class="text-center">{{ $row['seminar_reguler'] }}</td>
                                        <td class="text-center">{{ $row['seminar_mbkm'] }}</td>
                                        <td class="text-center">{{ $row['sidang'] }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $row['aktif'] > 0 ? 'success' : 'secondary' }} badge-lg">{{ $row['aktif'] }}</span>
                                        </td>
                                        <td class="text-center">{{ $row['selesai'] }}</td>
                                        <td class="text-center"><strong>{{ $row['total'] }}</strong></td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-primary btn-detail-penguji"
                                                    data-url="{{ route('admin.dosen-workload.penguji-detail', $row['dosen_id']) }}"
                                                    data-nama="{{ $row['nama'] }}">
                                                <i class="fas fa-list"></i> Detail
                                            </button>
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
                <h5 class="modal-title"><i class="fas fa-gavel"></i> Detail Penugasan Penguji — <span id="detailModalNama"></span></h5>
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
    if ($.fn.DataTable && $('#rekapPengujiTable').length) {
        $('#rekapPengujiTable').DataTable({
            order: [[9, 'desc']],
            pageLength: 25,
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });
    }

    $('.btn-detail-penguji').on('click', function () {
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
