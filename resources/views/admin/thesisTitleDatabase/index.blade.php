@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-book"></i> Database Judul Skripsi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Database Judul</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="alert alert-warning">
                <strong>Detail import:</strong>
                <ul class="mb-0 mt-1">
                    @foreach(session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-search"></i> Cari Judul</h3>
                <div class="ml-auto">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addTitleModal">
                        <i class="fas fa-plus"></i> Input Manual
                    </button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#importCsvModal">
                        <i class="fas fa-file-csv"></i> Import CSV
                    </button>
                    <a href="{{ route('admin.thesis-title-database.template') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-download"></i> Template CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.thesis-title-database.index') }}">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control"
                               placeholder="Ketik kata kunci judul (Indonesia/English), nama, atau NIM..."
                               value="{{ $query }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                            @if($query)
                                <a href="{{ route('admin.thesis-title-database.index') }}" class="btn btn-outline-secondary">Reset</a>
                            @endif
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Pencarian memakai semua kata kunci (AND). Contoh: <code>kecemasan remaja</code>
                    </small>
                </form>
            </div>
        </div>

        <div class="card card-outline card-info mb-3">
            <div class="card-body py-3">
                <i class="fas fa-info-circle"></i>
                Sumber otomatis: <strong>judul akhir sidang</strong> (<code>final_title</code> / <code>final_title_en</code>).
                Judul arsip lama dapat ditambah via <strong>Input Manual</strong> atau <strong>Import CSV</strong>.
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number">{{ $summary['total'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-gavel"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dari Sidang</span>
                        <span class="info-box-number">{{ $summary['from_sidang'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-secondary"><i class="fas fa-keyboard"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Manual/Import</span>
                        <span class="info-box-number">{{ $summary['manual'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-fingerprint"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Unik (ID)</span>
                        <span class="info-box-number">{{ $summary['unique_titles'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-dark"><i class="fas fa-language"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ada Judul EN</span>
                        <span class="info-box-number">{{ $summary['with_english'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box mb-0">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clone"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Duplikat</span>
                        <span class="info-box-number">{{ $summary['duplicate_entries'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($summary['duplicate_entries'] > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Ditemukan <strong>{{ $summary['duplicate_entries'] }}</strong> entri dengan judul yang sama dengan entri lain.
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Daftar Judul Skripsi</h3>
            </div>
            <div class="card-body p-0">
                @if($entries->isEmpty())
                    <p class="text-muted text-center py-5 mb-0">
                        @if($query)
                            Tidak ada judul yang cocok dengan kata kunci "<strong>{{ $query }}</strong>".
                        @else
                            Belum ada data. Tambahkan via hasil sidang, input manual, atau import CSV.
                        @endif
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm mb-0" id="titleDatabaseTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Prodi</th>
                                    <th>Jalur</th>
                                    <th>Sumber</th>
                                    <th style="min-width: 260px;">Judul (Indonesia)</th>
                                    <th style="min-width: 220px;">Judul (English)</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $i => $entry)
                                    <tr class="{{ $entry['is_duplicate'] ? 'table-warning' : '' }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $entry['mahasiswa'] }}</td>
                                        <td>{{ $entry['nim'] }}</td>
                                        <td>{{ $entry['prodi'] }}</td>
                                        <td><span class="badge badge-primary">{{ $entry['type'] }}</span></td>
                                        <td>
                                            <span class="badge badge-{{ $entry['is_manual'] ? 'secondary' : 'success' }}">
                                                {{ $entry['source'] }}
                                            </span>
                                        </td>
                                        <td>{!! $query ? highlight_keywords($entry['title'], $query) : e($entry['title']) !!}</td>
                                        <td>
                                            @if($entry['title_en'])
                                                {!! $query ? highlight_keywords($entry['title_en'], $query) : e($entry['title_en']) !!}
                                            @else
                                                <span class="text-muted font-italic">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $entry['year'] }}</td>
                                        <td>
                                            @if($entry['is_duplicate'])
                                                <span class="badge badge-warning" title="{{ $entry['duplicate_reason'] ?? '' }}">
                                                    Duplikat ({{ $entry['duplicate_count'] }})
                                                </span>
                                            @else
                                                <span class="badge badge-success">Unik</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            @if($entry['is_manual'] && $entry['manual_id'])
                                                <form action="{{ route('admin.thesis-title-database.destroy', $entry['manual_id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus entri manual ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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

{{-- Modal Input Manual --}}
<div class="modal fade" id="addTitleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.thesis-title-database.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Tambah Judul Manual</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul (Indonesia) <span class="text-danger">*</span></label>
                        <textarea name="title" class="form-control" rows="3" required maxlength="500" placeholder="Judul skripsi dalam Bahasa Indonesia"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Judul (English)</label>
                        <textarea name="title_en" class="form-control" rows="3" maxlength="500" placeholder="Thesis title in English (opsional)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Mahasiswa</label>
                                <input type="text" name="mahasiswa_nama" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="text" name="nim" class="form-control" maxlength="30">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Prodi</label>
                                <input type="text" name="prodi" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jalur</label>
                                <select name="type" class="form-control">
                                    <option value="">-</option>
                                    <option value="skripsi">Skripsi Reguler</option>
                                    <option value="mbkm">MBKM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="text" name="year" class="form-control" maxlength="4" placeholder="2024">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Catatan</label>
                        <textarea name="note" class="form-control" rows="2" maxlength="2000" placeholder="Opsional, mis. sumber arsip"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Import CSV --}}
<div class="modal fade" id="importCsvModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.thesis-title-database.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-csv"></i> Import CSV</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <strong>Format kolom:</strong> judul, judul_en, mahasiswa, nim, prodi, jalur, tahun, catatan<br>
                        Baris pertama = header. Kolom <code>judul</code> wajib diisi.<br>
                        <a href="{{ route('admin.thesis-title-database.template') }}">Unduh template CSV</a>
                    </div>
                    <div class="form-group mb-0">
                        <label>File CSV</label>
                        <input type="file" name="csv_file" class="form-control-file" accept=".csv,.txt" required>
                        <small class="text-muted">Maks. 5 MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-upload"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    if ($.fn.DataTable && $('#titleDatabaseTable').length) {
        $('#titleDatabaseTable').DataTable({
            order: [[8, 'desc']],
            pageLength: 50,
            scrollX: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });
    }
});
</script>
@endsection
