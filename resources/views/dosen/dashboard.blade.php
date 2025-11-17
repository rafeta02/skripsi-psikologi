@extends('layouts.dosen')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Dosen
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5>Selamat Datang, {{ $dosen->nama }}</h5>
                            <p class="text-muted">NIDN: {{ $dosen->nidn }} | NIP: {{ $dosen->nip }}</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalMahasiswaBimbingan }}</h3>
                                    <p>Mahasiswa Bimbingan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalTasksPending }}</h3>
                                    <p>Task Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <a href="{{ route('dosen.task-assignments') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalTasksCompleted }}</h3>
                                    <p>Task Completed</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <a href="{{ route('dosen.task-assignments') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalScores }}</h3>
                                    <p>Scores Given</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <a href="{{ route('dosen.scores') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Assignments -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-tasks"></i> Recent Assignments
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if($recentAssignments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Mahasiswa</th>
                                                        <th>NIM</th>
                                                        <th>Type</th>
                                                        <th>Stage</th>
                                                        <th>Role</th>
                                                        <th>Status</th>
                                                        <th>Assigned At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentAssignments as $assignment)
                                                        <tr>
                                                            <td>{{ $assignment->application->mahasiswa->nama ?? 'N/A' }}</td>
                                                            <td>{{ $assignment->application->mahasiswa->nim ?? 'N/A' }}</td>
                                                            <td>
                                                                <span class="badge badge-primary">
                                                                    {{ strtoupper($assignment->application->type ?? 'N/A') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-info">
                                                                    {{ ucfirst($assignment->application->stage ?? 'N/A') }}
                                                                </span>
                                                            </td>
                                                            <td>{{ ucfirst($assignment->role) }}</td>
                                                            <td>
                                                                @if($assignment->status == 'assigned')
                                                                    <span class="badge badge-warning">{{ ucfirst($assignment->status) }}</span>
                                                                @elseif($assignment->status == 'accepted')
                                                                    <span class="badge badge-success">{{ ucfirst($assignment->status) }}</span>
                                                                @else
                                                                    <span class="badge badge-danger">{{ ucfirst($assignment->status) }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $assignment->assigned_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Tidak ada assignment terbaru.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
