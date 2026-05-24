@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2><i class="fas fa-user"></i> Profile Mahasiswa</h2>
        <p class="text-muted">Informasi lengkap profil Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ asset('img/default.jpg') }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #22004C;">
                    </div>
                    <h4 class="mb-1">{{ $mahasiswa->nama }}</h4>
                    <p class="text-muted mb-2"><strong>{{ $mahasiswa->nim }}</strong></p>
                    <span class="badge badge-primary mb-3">Mahasiswa Aktif</span>
                    <hr>
                    <a href="{{ route('frontend.mahasiswa-profile.edit') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Personal</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">NIM</h6>
                            <p class="mb-0"><strong>{{ $mahasiswa->nim }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Nama Lengkap</h6>
                            <p class="mb-0"><strong>{{ $mahasiswa->nama }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Tempat Lahir</h6>
                            <p class="mb-0">{{ $mahasiswa->tempat_lahir ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Tanggal Lahir</h6>
                            <p class="mb-0">{{ $mahasiswa->tanggal_lahir ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Gender</h6>
                            <p class="mb-0">
                                @if($mahasiswa->gender == 'male')
                                    Laki-Laki
                                @elseif($mahasiswa->gender == 'female')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Kelas</h6>
                            <p class="mb-0">{{ $mahasiswa->kelas ?? '-' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-1">Alamat</h6>
                            <p class="mb-0">{{ $mahasiswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-university"></i> Informasi Akademik</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Fakultas</h6>
                            <p class="mb-0"><strong>{{ $mahasiswa->fakultas->name ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Program Studi</h6>
                            <p class="mb-0"><strong>{{ $mahasiswa->prodi->name ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Jenjang</h6>
                            <p class="mb-0">{{ $mahasiswa->jenjang->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Tahun Masuk</h6>
                            <p class="mb-0">{{ $mahasiswa->tahun_masuk ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
