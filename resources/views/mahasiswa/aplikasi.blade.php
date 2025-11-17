@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-file-alt"></i> Aplikasi Saya</h2>
            <p class="text-muted">Daftar semua aplikasi skripsi Anda</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Aplikasi Baru
            </a>
        </div>
    </div>

    @if(count($applications) > 0)
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Jenis</th>
                                <th>Tahap</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-uppercase"><strong>{{ $app->type }}</strong></td>
                                    <td class="text-capitalize">{{ $app->stage }}</td>
                                    <td>
                                        @if($app->status == 'submitted')
                                            <span class="badge badge-warning">Submitted</span>
                                        @elseif($app->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @elseif($app->status == 'revision')
                                            <span class="badge badge-warning"><i class="fas fa-edit"></i> Perlu Revisi</span>
                                        @elseif($app->status == 'scheduled')
                                            <span class="badge badge-info">Scheduled</span>
                                        @elseif($app->status == 'done')
                                            <span class="badge badge-secondary">Done</span>
                                        @endif
                                    </td>
                                    <td>{{ $app->submitted_at ?? $app->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if($app->status == 'revision' && isset($app->revision_notes))
                                            <span class="text-warning"><i class="fas fa-exclamation-circle"></i> {{ Str::limit($app->revision_notes, 50) }}</span>
                                        @else
                                            {{ $app->notes ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('frontend.applications.show', $app->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if($app->status == 'revision')
                                            @if($app->type == 'mbkm')
                                                <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Revisi
                                                </a>
                                            @elseif($app->type == 'skripsi')
                                                <a href="{{ route('frontend.skripsi-registrations.index') }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Revisi
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Aplikasi</h3>
                <p class="text-muted mb-4">Anda belum memiliki aplikasi skripsi. Mulai dengan memilih jalur skripsi Anda.</p>
                <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-route"></i> Pilih Jalur Skripsi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
