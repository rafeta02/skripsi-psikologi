@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2><i class="fas fa-users"></i> Bimbingan Skripsi</h2>
        <p class="text-muted">Informasi dosen pembimbing dan progress bimbingan</p>
    </div>

    @if(count($bimbinganData) > 0)
        @foreach($bimbinganData as $data)
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-graduation-cap"></i> 
                        {{ strtoupper($data->type) }} - {{ ucfirst($data->stage) }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status Aplikasi:</strong>
                            @if($data->status == 'submitted')
                                <span class="badge badge-warning">Submitted</span>
                            @elseif($data->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($data->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @elseif($data->status == 'scheduled')
                                <span class="badge badge-info">Scheduled</span>
                            @elseif($data->status == 'done')
                                <span class="badge badge-secondary">Done</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Daftar:</strong> {{ $data->submitted_at ?? $data->created_at->format('d M Y') }}
                        </div>
                    </div>

                    @if($data->supervisors && count($data->supervisors) > 0)
                        <h5 class="mt-4"><i class="fas fa-user-tie"></i> Dosen Pembimbing</h5>
                        <div class="row">
                            @foreach($data->supervisors as $supervisor)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-primary h-100" style="border-left: 4px solid #4e73df;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1">{{ $supervisor->lecturer->nama ?? 'N/A' }}</h5>
                                                    <p class="mb-1 text-muted small">
                                                        NIDN: {{ $supervisor->lecturer->nidn ?? '-' }}
                                                    </p>
                                                    <p class="mb-0">
                                                        Status: 
                                                        @if($supervisor->status == 'assigned')
                                                            <span class="badge badge-warning">Assigned</span>
                                                        @elseif($supervisor->status == 'accepted')
                                                            <span class="badge badge-success">Accepted</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if($supervisor->note)
                                                <div class="mt-3">
                                                    <small><strong>Note:</strong></small>
                                                    <p class="small text-muted mb-0">{{ $supervisor->note }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> Belum ada dosen pembimbing yang ditugaskan untuk aplikasi ini.
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('frontend.applications.show', $data->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Lihat Detail Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Data Bimbingan</h3>
                <p class="text-muted mb-4">Anda belum memiliki data bimbingan. Silakan buat aplikasi skripsi terlebih dahulu.</p>
                <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-route"></i> Pilih Jalur Skripsi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
