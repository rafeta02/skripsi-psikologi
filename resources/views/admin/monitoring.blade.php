@extends('layouts.admin')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-chart-line"></i> Monitoring Skripsi & MBKM
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Monitoring</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Applications List with Timeline -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-list"></i> Daftar Aplikasi & Progress
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-primary">{{ $applications->total() }} Total</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach($applications as $application)
                            <div class="card mb-3 mx-3 mt-3">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="mb-0">
                                                <i class="fas fa-user-graduate"></i> 
                                                {{ $application->mahasiswa->nama ?? 'N/A' }}
                                                <small class="text-muted">- {{ $application->mahasiswa->nim ?? 'N/A' }}</small>
                                            </h5>
                                            <small class="text-muted">
                                                {{ $application->mahasiswa->prodi->name ?? 'N/A' }}
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <span class="badge badge-{{ $application->type == 'skripsi' ? 'primary' : 'info' }} mr-2">
                                                {{ strtoupper($application->type) }}
                                            </span>
                                            @if($application->status == 'submitted')
                                                <span class="badge badge-warning">Submitted</span>
                                            @elseif($application->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($application->status == 'scheduled')
                                                <span class="badge badge-info">Scheduled</span>
                                            @elseif($application->status == 'revision')
                                                <span class="badge badge-warning">Revision</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif($application->status == 'done')
                                                <span class="badge badge-secondary">Done</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Timeline Component -->
                                    @include('components.thesis-timeline', [
                                        'application' => $application,
                                        'compact' => true
                                    ])

                                    <!-- Quick Info -->
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>Pembimbing:</strong>
                                            @php
                                                $pembimbing = $application->assignments->where('role', 'pembimbing')->first();
                                            @endphp
                                            {{ $pembimbing ? $pembimbing->dosen->nama : 'Belum ditugaskan' }}
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($applications->count() == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada aplikasi yang tersedia</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
