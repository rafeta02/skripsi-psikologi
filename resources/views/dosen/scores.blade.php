@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Penilaian Sidang',
    'subtitle' => $pendingCount > 0
        ? $pendingCount . ' penilaian menunggu diisi setelah sidang dilaksanakan'
        : 'Semua penilaian selesai',
])

<div class="alert alert-info mb-3">
    <i class="fas fa-info-circle mr-1"></i>
    Pembimbing dan penguji dapat mengisi nilai setelah <strong>waktu sidang skripsi lewat</strong>
    dan jadwal sudah diverifikasi admin.
</div>

<div class="row">
    <div class="col-12">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($scores->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Program Studi</th>
                                    <th class="text-center">Tahap</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Nilai</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores as $index => $score)
                                    @php
                                        $app = $score->application ?? $score->application_result_defence?->application;
                                        $resultDefense = $score->application_result_defence;
                                        $isPreReport = !$resultDefense;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $app->mahasiswa->nama ?? 'N/A' }}</strong>
                                            <br><small class="text-muted">{{ $app->mahasiswa->nim ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $app->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($isPreReport)
                                                <span class="badge badge-primary">Setelah sidang</span>
                                            @else
                                                @php
                                                    $resultLabels = [
                                                        'passed' => 'Lulus tanpa revisi',
                                                        'revision' => 'Lulus dengan revisi',
                                                        'failed' => 'Tidak Lulus',
                                                    ];
                                                @endphp
                                                <span class="badge badge-info">{{ $resultLabels[$resultDefense->result ?? ''] ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($score->isComplete())
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning">Perlu diisi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($score->isComplete())
                                                <strong>{{ number_format($score->score, 2) }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($score->updated_at)->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('dosen.application-scores.edit', $score) }}" class="btn btn-sm btn-primary">
                                                {{ $score->isComplete() ? 'Ubah' : 'Isi Nilai' }}
                                            </a>
                                            @php
                                                $resultReport = $app->resultDefense ?? null;
                                            @endphp
                                            @if($resultReport)
                                                <a href="{{ route('dosen.application-result-defenses.show', $resultReport->id) }}" class="btn btn-sm btn-outline-secondary mt-1">
                                                    <i class="fas fa-file-alt"></i> Laporan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-star fa-2x mb-2 d-block"></i>
                        Belum ada tugas penilaian sidang.
                        <br><small>Penilaian akan muncul setelah sidang skripsi dilaksanakan (waktu sidang lewat).</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-lg-6">
                @include('partials.dosen.quick-actions')
            </div>
            <div class="col-lg-6">
                @include('partials.dosen.activity-timeline')
            </div>
        </div>
    </div>
</div>
@endsection
