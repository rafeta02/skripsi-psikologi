@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-users mr-2"></i> Mahasiswa Bimbingan Aktif
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-user mr-2"></i> {{ $dosen->nama }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-id-card mr-2"></i> NIDN: {{ $dosen->nidn }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                                <div><i class="fas fa-user-graduate mr-2"></i> <strong>{{ $mahasiswaBimbingan->count() }}</strong> Mahasiswa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body p-0">
                    @if($mahasiswaBimbingan->count() > 0)
                        <div class="table-responsive">
                            <table class="table-modern table-modern-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenis</th>
                                        <th>Tahap</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaBimbingan as $index => $bimbingan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 32px; height: 32px; background: var(--dosen-accent); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-2);">
                                                        <i class="fas fa-user" style="font-size: 12px; color: white;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">{{ $bimbingan->application->mahasiswa->nama }}</div>
                                                        <div class="text-xs text-gray-600">{{ $bimbingan->application->mahasiswa->nim }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm">
                                                <div>{{ $bimbingan->application->mahasiswa->prodi->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-600">{{ $bimbingan->application->mahasiswa->jenjang->name ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-modern badge-modern-primary">
                                                    {{ strtoupper($bimbingan->application->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-modern badge-modern-info">
                                                    {{ ucfirst($bimbingan->application->stage) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($bimbingan->application->status == 'submitted')
                                                    <span class="badge-modern badge-modern-warning">Submitted</span>
                                                @elseif($bimbingan->application->status == 'approved')
                                                    <span class="badge-modern badge-modern-success">Approved</span>
                                                @elseif($bimbingan->application->status == 'rejected')
                                                    <span class="badge-modern badge-modern-danger">Rejected</span>
                                                @elseif($bimbingan->application->status == 'scheduled')
                                                    <span class="badge-modern badge-modern-info">Scheduled</span>
                                                @elseif($bimbingan->application->status == 'revision')
                                                    <span class="badge-modern badge-modern-warning">Revision</span>
                                                @elseif($bimbingan->application->status == 'done')
                                                    <span class="badge-modern badge-modern-secondary">Done</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $bimbingan->assigned_at ? \Carbon\Carbon::parse($bimbingan->assigned_at)->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-modern btn-modern-sm btn-modern-primary" data-toggle="modal" data-target="#timelineModal{{ $bimbingan->application->id }}">
                                                        <i class="fas fa-route"></i> Timeline
                                                    </button>
                                                    <a href="{{ route('admin.applications.show', $bimbingan->application->id) }}" class="btn-modern btn-modern-sm btn-modern-outline">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Tidak Ada Mahasiswa Bimbingan</h4>
                            <p class="text-muted">Anda belum memiliki mahasiswa bimbingan aktif saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Modals -->
@foreach($mahasiswaBimbingan as $bimbingan)
<div class="modal fade" id="timelineModal{{ $bimbingan->application->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); color: white;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-route mr-2"></i> Timeline Progress - {{ $bimbingan->application->mahasiswa->nama }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                @include('components.thesis-timeline', [
                    'application' => $bimbingan->application,
                    'compact' => false
                ])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
