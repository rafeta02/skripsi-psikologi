@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Laporan Hasil Sidang',
    'subtitle' => $resultDefenses->count() > 0
        ? $resultDefenses->count() . ' laporan dari mahasiswa bimbingan/pengujian'
        : 'Belum ada laporan hasil sidang',
])

<div class="row">
    <div class="col-12">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($resultDefenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Judul Akhir</th>
                                    <th class="text-center">Hasil</th>
                                    <th class="text-center">Validasi Admin</th>
                                    <th class="text-center">Tgl Kirim</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultDefenses as $index => $resultDefense)
                                    @php
                                        $mahasiswa = $resultDefense->application?->mahasiswa;
                                        $resultLabels = \App\Models\ApplicationResultDefense::RESULT_SELECT;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $mahasiswa->nama ?? '-' }}</strong>
                                            <br><small class="text-muted">{{ $mahasiswa->nim ?? '-' }}</small>
                                        </td>
                                        <td class="text-left">{{ \Illuminate\Support\Str::limit($resultDefense->final_title ?? '-', 60) }}</td>
                                        <td>
                                            @php
                                                $resultClass = match ($resultDefense->result) {
                                                    'passed' => 'success',
                                                    'revision' => 'warning',
                                                    'failed' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $resultClass }}">
                                                {{ $resultLabels[$resultDefense->result] ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{!! $resultDefense->adminValidationStatusHtml() !!}</td>
                                        <td>{{ $resultDefense->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('dosen.application-result-defenses.show', $resultDefense->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-file-alt"></i> Lihat Laporan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-file-alt fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada laporan hasil sidang.</p>
                        <small>Laporan akan muncul setelah mahasiswa mengirim dan admin memvalidasi laporan hasil sidang.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
