@extends('layouts.frontend')

@section('content')
<style>
    .profile-edit-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 0;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 2.5rem;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .profile-header h1 {
        margin: 0 0 0.5rem;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .profile-header p {
        margin: 0;
        opacity: 0.9;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .btn-save {
        padding: 0.85rem 2.5rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.05rem;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
        color: white;
    }
    
    .btn-cancel {
        padding: 0.85rem 2.5rem;
        background: #e2e8f0;
        color: #4a5568;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.05rem;
        margin-left: 1rem;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cancel:hover {
        background: #cbd5e0;
        color: #2d3748;
        text-decoration: none;
    }
</style>

<div class="container profile-edit-container">
    <div class="profile-header">
        <h1><i class="fas fa-user-plus"></i> Buat Profil Mahasiswa</h1>
        <p>Isi semua data profil Anda dengan lengkap</p>
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="profile-card">
        <form method="POST" action="{{ route('frontend.mahasiswa-profile.store') }}">
            @csrf
            
            <div class="form-section">
                <h3 class="form-section-title">Data Identitas</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="nim">NIM</label>
                            <input class="form-control {{ $errors->has('nim') ? 'is-invalid' : '' }}" type="text" name="nim" id="nim" value="{{ old('nim') }}" required>
                            @if($errors->has('nim'))
                                <span class="text-danger">{{ $errors->first('nim') }}</span>
                            @endif
                            <small class="form-text text-muted">NIM harus unik dan tidak boleh sama dengan mahasiswa lain</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="nama">Nama Lengkap</label>
                            <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
                            @if($errors->has('nama'))
                                <span class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="tempat_lahir">Tempat Lahir</label>
                            <input class="form-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}" type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                            @if($errors->has('tempat_lahir'))
                                <span class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="tanggal_lahir">Tanggal Lahir</label>
                            <input class="form-control date {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @if($errors->has('tanggal_lahir'))
                                <span class="text-danger">{{ $errors->first('tanggal_lahir') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">Jenis Kelamin</label>
                            <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                @foreach(App\Models\Mahasiswa::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="alamat">Alamat</label>
                            <textarea class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" name="alamat" id="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @if($errors->has('alamat'))
                                <span class="text-danger">{{ $errors->first('alamat') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">Data Akademik</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required" for="tahun_masuk">Tahun Masuk</label>
                            <input class="form-control {{ $errors->has('tahun_masuk') ? 'is-invalid' : '' }}" type="number" name="tahun_masuk" id="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" step="1" required>
                            @if($errors->has('tahun_masuk'))
                                <span class="text-danger">{{ $errors->first('tahun_masuk') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">Kelas</label>
                            <select class="form-control {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas" id="kelas" required>
                                <option value="">Pilih Kelas</option>
                                @foreach(App\Models\Mahasiswa::KELAS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('kelas') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('kelas'))
                                <span class="text-danger">{{ $errors->first('kelas') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required" for="fakultas_id">Fakultas</label>
                            <select class="form-control select2 {{ $errors->has('fakultas') ? 'is-invalid' : '' }}" name="fakultas_id" id="fakultas_id" required>
                                @foreach($fakultas as $id => $entry)
                                    <option value="{{ $id }}" {{ old('fakultas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('fakultas'))
                                <span class="text-danger">{{ $errors->first('fakultas') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required" for="jenjang_id">Jenjang</label>
                            <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id" required>
                                @foreach($jenjangs as $id => $entry)
                                    <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenjang'))
                                <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required" for="prodi_id">Program Studi</label>
                            <select class="form-control select2 {{ $errors->has('prodi') ? 'is-invalid' : '' }}" name="prodi_id" id="prodi_id" required>
                                @foreach($prodis as $id => $entry)
                                    <option value="{{ $id }}" {{ old('prodi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prodi'))
                                <span class="text-danger">{{ $errors->first('prodi') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4">
                <button class="btn btn-save" type="submit">
                    <i class="fas fa-save"></i> Buat Profil
                </button>
                <a href="{{ route('frontend.home') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
        $('.date').datetimepicker({
            format: '{{ config('panel.date_format') }}',
            locale: 'id',
        });
        
        $('.select2').select2();
    });
</script>
@endsection
@endsection

