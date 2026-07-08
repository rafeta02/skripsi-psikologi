@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Profil Dosen',
    'subtitle' => $dosen->nama . ' · NIDN ' . $dosen->nidn,
])

<div class="mhs-card">
    <div class="mhs-card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <img src="{{ asset('img/user.png') }}" alt="Profile" class="img-thumbnail rounded-circle" style="width: 160px; height: 160px; object-fit: cover;">
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th class="text-muted" style="width: 140px;">NIDN</th><td>{{ $dosen->nidn }}</td></tr>
                            <tr><th class="text-muted">NIP</th><td>{{ $dosen->nip }}</td></tr>
                            <tr><th class="text-muted">Tempat Lahir</th><td>{{ $dosen->tempat_lahir ?? '-' }}</td></tr>
                            <tr><th class="text-muted">Tanggal Lahir</th><td>{{ $dosen->tanggal_lahir ?? '-' }}</td></tr>
                            <tr><th class="text-muted">Jenis Kelamin</th><td>
                                @if($dosen->gender == 'male') Laki-laki
                                @elseif($dosen->gender == 'female') Perempuan
                                @else - @endif
                            </td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th class="text-muted" style="width: 140px;">Fakultas</th><td>{{ $dosen->fakultas->name ?? '-' }}</td></tr>
                            <tr><th class="text-muted">Program Studi</th><td>{{ $dosen->prodi->name ?? '-' }}</td></tr>
                            <tr><th class="text-muted">Jenjang</th><td>{{ $dosen->jenjang->name ?? '-' }}</td></tr>
                            <tr><th class="text-muted">Riset Grup</th><td>{{ $dosen->riset_grup->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>

                @if($dosen->keilmuans && $dosen->keilmuans->count() > 0)
                    <div class="mt-3">
                        <h6 class="text-muted mb-2">Bidang Keilmuan</h6>
                        @foreach($dosen->keilmuans as $keilmuan)
                            <span class="badge badge-primary mr-1 mb-1">{{ $keilmuan->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('frontend.dosen-profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
