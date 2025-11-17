@extends('layouts.dosen')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user"></i> Profile Dosen
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="profile-image mb-3">
                                <img src="{{ asset('img/user.png') }}" alt="Profile" class="img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $dosen->nama }}</h3>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 150px;">NIDN</th>
                                            <td>{{ $dosen->nidn }}</td>
                                        </tr>
                                        <tr>
                                            <th>NIP</th>
                                            <td>{{ $dosen->nip }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat Lahir</th>
                                            <td>{{ $dosen->tempat_lahir ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Lahir</th>
                                            <td>{{ $dosen->tanggal_lahir ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($dosen->gender == 'male')
                                                    Laki-Laki
                                                @elseif($dosen->gender == 'female')
                                                    Perempuan
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 150px;">Fakultas</th>
                                            <td>{{ $dosen->fakultas->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>{{ $dosen->prodi->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenjang</th>
                                            <td>{{ $dosen->jenjang->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Research Group</th>
                                            <td>{{ $dosen->riset_grup->name ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($dosen->keilmuans && $dosen->keilmuans->count() > 0)
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5>Bidang Keilmuan</h5>
                                        <div class="d-flex flex-wrap">
                                            @foreach($dosen->keilmuans as $keilmuan)
                                                <span class="badge badge-primary mr-2 mb-2" style="font-size: 0.9em; padding: 8px 12px;">
                                                    {{ $keilmuan->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <a href="{{ route('frontend.dosen-profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
