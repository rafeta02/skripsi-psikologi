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
                                <i class="fas fa-star mr-2"></i> Application Scores
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-user mr-2"></i> {{ $dosen->nama }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-id-card mr-2"></i> NIDN: {{ $dosen->nidn }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                                <div><i class="fas fa-clipboard-check mr-2"></i> <strong>{{ $scores->count() }}</strong> Scores</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scores List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body p-0">
                    @if($scores->count() > 0)
                        <div class="table-responsive">
                            <table class="table-modern table-modern-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenis</th>
                                        <th>Tahap</th>
                                        <th>Nilai</th>
                                        <th>Catatan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scores as $index => $score)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 32px; height: 32px; background: var(--dosen-accent); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-2);">
                                                        <i class="fas fa-user" style="font-size: 12px; color: white;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">{{ $score->application_result_defence->application->mahasiswa->nama ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600">{{ $score->application_result_defence->application->mahasiswa->nim ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm">{{ $score->application_result_defence->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge-modern badge-modern-primary">
                                                    {{ strtoupper($score->application_result_defence->application->type ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-modern badge-modern-info">
                                                    {{ ucfirst($score->application_result_defence->application->stage ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--dosen-primary), var(--dosen-secondary)); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-2);">
                                                        <span class="text-white font-weight-bold">{{ $score->score }}</span>
                                                    </div>
                                                    <div>
                                                        @if($score->score >= 80)
                                                            <span class="badge-modern badge-modern-success">Excellent</span>
                                                        @elseif($score->score >= 70)
                                                            <span class="badge-modern badge-modern-info">Good</span>
                                                        @elseif($score->score >= 60)
                                                            <span class="badge-modern badge-modern-warning">Fair</span>
                                                        @else
                                                            <span class="badge-modern badge-modern-danger">Poor</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm" style="max-width: 200px;">
                                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $score->note ?? '-' }}">
                                                    {{ $score->note ?? '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ \Carbon\Carbon::parse($score->created_at)->format('d M Y') }}
                                                <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($score->created_at)->format('H:i') }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.application-scores.show', $score->id) }}" class="btn-modern btn-modern-sm btn-modern-primary">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                                <i class="fas fa-star fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Belum Ada Score</h4>
                            <p class="text-muted">Anda belum memberikan score apapun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
