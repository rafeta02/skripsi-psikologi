@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-tie"></i> Rekap Beban Pembimbing</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Rekap Pembimbing</li>
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
                        <span class="info-box-text">Dosen Pembimbing</span>
                        <span class="info-box-number">{{ $summary['total_dosen'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bimbingan Aktif</span>
                        <span class="info-box-number">{{ $summary['total_aktif'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu Respons</span>
                        <span class="info-box-number">{{ $summary['total_menunggu'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-chart-bar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Beban Tertinggi (aktif)</span>
                        <span class="info-box-number">{{ $summary['max_beban'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Daftar Beban Pembimbing per Dosen</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.dosen-workload.rekap-penguji') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-gavel"></i> Lihat Rekap Penguji
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>Bimbingan aktif</strong> = penugasan diterima dan mahasiswa belum selesai/rejected.
                    Untuk <strong>MBKM kelompok</strong>, beban dihitung per mahasiswa (ketua + anggota), bukan per kelompok.
                    Klik <strong>Detail</strong> untuk melihat daftar mahasiswa bimbingan.
                </div>

                @if($recap->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Belum ada data penugasan pembimbing.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="rekapPembimbingTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Dosen</th>
                                    <th>NIDN</th>
                                    <th>Prodi</th>
                                    <th class="text-center bg-success text-white">Aktif</th>
                                    <th class="text-center">Menunggu</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Reguler</th>
                                    <th class="text-center">MBKM</th>
                                    <th class="text-center">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recap as $i => $row)
                                    <tr class="{{ $row['aktif'] >= 5 ? 'table-warning' : '' }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td><strong>{{ $row['nama'] }}</strong></td>
                                        <td>{{ $row['nidn'] }}</td>
                                        <td>{{ $row['prodi'] }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $row['aktif'] > 0 ? 'success' : 'secondary' }} badge-lg">{{ $row['aktif'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($row['menunggu'] > 0)
                                                <span class="badge badge-warning">{{ $row['menunggu'] }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $row['selesai'] }}</td>
                                        <td class="text-center">{{ $row['reguler'] }}</td>
                                        <td class="text-center">{{ $row['mbkm'] }}</td>
                                        <td class="text-center"><strong>{{ $row['total'] }}</strong></td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-primary btn-detail-pembimbing"
                                                    data-url="{{ route('admin.dosen-workload.pembimbing-detail', $row['dosen_id']) }}"
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
                <h5 class="modal-title"><i class="fas fa-user-tie"></i> Detail Bimbingan — <span id="detailModalNama"></span></h5>
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
    if ($.fn.DataTable && $('#rekapPembimbingTable').length) {
        $('#rekapPembimbingTable').DataTable({
            order: [[4, 'desc']],
            pageLength: 25,
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });
    }

    $('.btn-detail-pembimbing').on('click', function () {
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
