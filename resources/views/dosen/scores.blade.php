@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Penilaian Sidang',
    'subtitle' => $pendingCount > 0 ? $pendingCount . ' penilaian menunggu diisi' : 'Semua penilaian selesai',
])

<div class="row">
    <div class="col-lg-8">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($scores->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>Tahap</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                    <th></th>
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
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @include('partials.dosen.quick-actions')
        @include('partials.dosen.activity-timeline')
    </div>
</div>
@endsection
