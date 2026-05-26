@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-star mr-2"></i> Penilaian Sidang (ApplicationScore)
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-user mr-2"></i> {{ $dosen->nama }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-id-card mr-2"></i> NIDN: {{ $dosen->nidn }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            @if($pendingCount > 0)
                                <span class="badge badge-warning badge-lg px-3 py-2">
                                    {{ $pendingCount }} penilaian menunggu
                                </span>
                            @else
                                <span class="badge badge-light badge-lg px-3 py-2">Semua penilaian selesai</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                        <th>Hasil Sidang</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scores as $index => $score)
                                        @php
                                            $resultDefense = $score->application_result_defence;
                                            $validated = $resultDefense?->isValidatedByAdmin();
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="font-weight-semibold">{{ $resultDefense->application->mahasiswa->nama ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-600">{{ $resultDefense->application->mahasiswa->nim ?? 'N/A' }}</div>
                                            </td>
                                            <td class="text-sm">{{ $resultDefense->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $resultLabels = [
                                                        'passed' => 'Lulus',
                                                        'revision' => 'Revisi',
                                                        'failed' => 'Tidak Lulus',
                                                    ];
                                                @endphp
                                                <span class="badge-modern badge-modern-info">
                                                    {{ $resultLabels[$resultDefense->result ?? ''] ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(!$validated)
                                                    <span class="badge-modern badge-modern-warning">Menunggu validasi admin</span>
                                                @elseif($score->isComplete())
                                                    <span class="badge-modern badge-modern-success">Selesai</span>
                                                @else
                                                    <span class="badge-modern badge-modern-primary">Perlu diisi</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($score->isComplete())
                                                    <strong>{{ number_format($score->score, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($score->updated_at)->format('d M Y H:i') }}
                                            </td>
                                            <td>
                                                @if($validated)
                                                    <a href="{{ route('dosen.application-scores.edit', $score) }}" class="btn-modern btn-modern-sm btn-modern-primary">
                                                        <i class="fas fa-edit"></i>
                                                        {{ $score->isComplete() ? 'Ubah' : 'Isi Nilai' }}
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Belum tersedia</span>
                                                @endif
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
                            <h4 class="text-muted">Belum Ada Tugas Penilaian</h4>
                            <p class="text-muted mb-0">Penilaian sidang akan muncul setelah admin memvalidasi laporan hasil sidang mahasiswa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
