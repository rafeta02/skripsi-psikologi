@extends('layouts.dosen')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users"></i> Mahasiswa Bimbingan Aktif
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Dosen: {{ $dosen->nama }}</h5>
                            <p class="text-muted">NIDN: {{ $dosen->nidn }} | NIP: {{ $dosen->nip }}</p>
                        </div>
                    </div>

                    @if($mahasiswaBimbingan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenjang</th>
                                        <th>Type</th>
                                        <th>Stage</th>
                                        <th>Status Application</th>
                                        <th>Assigned At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaBimbingan as $index => $bimbingan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $bimbingan->application->mahasiswa->nim }}</td>
                                            <td>{{ $bimbingan->application->mahasiswa->nama }}</td>
                                            <td>{{ $bimbingan->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>{{ $bimbingan->application->mahasiswa->jenjang->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ strtoupper($bimbingan->application->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst($bimbingan->application->stage) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($bimbingan->application->status == 'submitted')
                                                    <span class="badge badge-warning">Submitted</span>
                                                @elseif($bimbingan->application->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($bimbingan->application->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @elseif($bimbingan->application->status == 'scheduled')
                                                    <span class="badge badge-info">Scheduled</span>
                                                @elseif($bimbingan->application->status == 'done')
                                                    <span class="badge badge-secondary">Done</span>
                                                @endif
                                            </td>
                                            <td>{{ $bimbingan->assigned_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.applications.show', $bimbingan->application->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Tidak ada mahasiswa bimbingan aktif saat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
