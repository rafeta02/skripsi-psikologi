@extends('layouts.admin')
@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <span>
            <i class="fas fa-user-clock mr-2"></i> Watchlist Mahasiswa (Reguler)
            @if(($regulerWatchlistCount ?? 0) > 0)
                <span class="badge badge-warning ml-1">{{ $regulerWatchlistCount }}</span>
            @endif
        </span>
        <small class="text-muted">
            Mahasiswa yang belum mendaftar sidang &gt; {{ $graceDays }} hari setelah validasi admin laporan hasil review
        </small>
    </div>

    <div class="card-body">
        <div class="alert alert-warning mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            Daftar ini diperbarui otomatis. Mahasiswa akan hilang dari watchlist setelah mendaftar sidang skripsi.
        </div>

        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-MahasiswaWatchlist text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Dosen Pembimbing</th>
                    <th width="140" class="text-center">Tgl Validasi Admin</th>
                    <th width="120" class="text-center">Lama Idle</th>
                    <th class="text-center">Hasil Review</th>
                    <th width="130" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <span>
            <i class="fas fa-file-upload mr-2"></i> Belum Lapor Hasil Sidang
            @if(($defenseResultWatchlistCount ?? 0) > 0)
                <span class="badge badge-danger ml-1">{{ $defenseResultWatchlistCount }}</span>
            @endif
        </span>
        <small class="text-muted">
            Belum lapor hasil sidang sejak {{ $defenseResultWarningStartDays }} hari setelah sidang (tetap tampil setelah 30 hari)
        </small>
    </div>

    <div class="card-body">
        <div class="alert alert-info mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            Mahasiswa muncul di sini jika sidang sudah lewat {{ $defenseResultWarningStartDays }} hari
            dan belum mengunggah Laporan Hasil Sidang. Daftar tetap tampil meskipun sudah lebih dari 30 hari.
            Gunakan tombol <strong>WA</strong> untuk mengingatkan mahasiswa.
        </div>

        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-DefenseResultWatchlist text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Dosen Pembimbing</th>
                    <th width="90" class="text-center">Jalur</th>
                    <th width="150" class="text-center">Tgl Sidang</th>
                    <th width="120" class="text-center">Lama Idle</th>
                    <th width="150" class="text-center">Status</th>
                    <th width="130" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-MahasiswaWatchlist th,
    .datatable-MahasiswaWatchlist td,
    .datatable-DefenseResultWatchlist th,
    .datatable-DefenseResultWatchlist td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    let regulerTableConfig = {
        buttons: [],
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.mahasiswa-watchlists.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false, className: 'text-center' },
            { data: 'mahasiswa_name', name: 'mahasiswa_name', orderable: false, searchable: false, className: 'text-center' },
            { data: 'pembimbing_name', name: 'pembimbing_name', orderable: false, searchable: false, className: 'text-center' },
            { data: 'validated_at', name: 'validated_at', orderable: false, searchable: false, className: 'text-center' },
            { data: 'idle_days', name: 'idle_days', orderable: false, searchable: false, className: 'text-center' },
            { data: 'result_label', name: 'result_label', orderable: false, searchable: false, className: 'text-center' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        orderCellsTop: true,
        order: [],
        pageLength: 25,
    };

    let defenseResultTableConfig = {
        buttons: [],
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: {
            url: "{{ route('admin.mahasiswa-watchlists.index') }}",
            data: function (d) {
                d.type = 'defense_result';
            }
        },
        columns: [
            { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false, className: 'text-center' },
            { data: 'mahasiswa_name', name: 'mahasiswa_name', orderable: false, searchable: false, className: 'text-center' },
            { data: 'pembimbing_name', name: 'pembimbing_name', orderable: false, searchable: false, className: 'text-center' },
            { data: 'jalur_label', name: 'jalur_label', orderable: false, searchable: false, className: 'text-center' },
            { data: 'defense_held_at', name: 'defense_held_at', orderable: false, searchable: false, className: 'text-center' },
            { data: 'days_since_defense', name: 'days_since_defense', orderable: false, searchable: false, className: 'text-center' },
            { data: 'status_label', name: 'status_label', orderable: false, searchable: false, className: 'text-center' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        orderCellsTop: true,
        order: [],
        pageLength: 25,
    };

    $('.datatable-MahasiswaWatchlist').DataTable(regulerTableConfig);
    $('.datatable-DefenseResultWatchlist').DataTable(defenseResultTableConfig);
});
</script>
@endsection
