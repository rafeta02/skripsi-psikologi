@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Naskah Sidang Skripsi',
    'subtitle' => $defenses->count() > 0
        ? $defenses->count() . ' sidang siap ditinjau sebelum pelaksanaan'
        : 'Belum ada sidang yang perlu ditinjau',
])

<div class="row">
    <div class="col-12">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($defenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Jalur</th>
                                    <th class="text-center">Peran Anda</th>
                                    <th class="text-center">Penguji</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($defenses as $index => $defense)
                                    @php
                                        $mahasiswa = $defense->application?->mahasiswa;
                                        $roleLabel = $defense->dosenRoleLabel($dosen->id) ?? '-';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $mahasiswa->nama ?? '-' }}</strong>
                                            <br><small class="text-muted">{{ $mahasiswa->nim ?? '-' }}</small>
                                        </td>
                                        <td class="text-left">{{ \Illuminate\Support\Str::limit($defense->title ?? '-', 60) }}</td>
                                        <td>
                                            @if(($defense->application->type ?? null) === 'mbkm')
                                                <span class="badge badge-info">MBKM</span>
                                            @else
                                                <span class="badge badge-success">Reguler</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-primary">{{ $roleLabel }}</span></td>
                                        <td class="text-left small">
                                            @if($defense->examiner1?->dosen)
                                                <div>P1: {{ $defense->examiner1->dosen->nama }}</div>
                                            @endif
                                            @if($defense->examiner2?->dosen)
                                                <div>P2: {{ $defense->examiner2->dosen->nama }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('dosen.skripsi-defenses.show', $defense->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-book-open"></i> Baca Naskah
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada naskah sidang yang dapat diakses.</p>
                        <small>Naskah akan tersedia setelah admin menyetujui pendaftaran sidang dan menetapkan penguji.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
