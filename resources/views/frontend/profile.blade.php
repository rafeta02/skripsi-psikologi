@extends('layouts.frontend')

@section('styles')
<style>
    .profile-container {
        padding: 2rem 0;
    }

    .profile-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(34, 0, 76, 0.2);
    }

    .profile-header h1 {
        margin: 0 0 0.5rem;
        font-size: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-header h1 i {
        font-size: 2.5rem;
    }

    .profile-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .profile-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .card-header-custom {
        font-size: 1.25rem;
        font-weight: 600;
        color: #22004C;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header-custom i {
        font-size: 1.5rem;
        color: #22004C;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-label.required::after {
        content: " *";
        color: #e53e3e;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        color: #2d3748;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #22004C;
        box-shadow: 0 0 0 3px rgba(34, 0, 76, 0.1);
    }

    .form-control.is-invalid {
        border-color: #e53e3e;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .help-block {
        display: block;
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(34, 0, 76, 0.3);
        color: white;
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .btn-delete {
        background: linear-gradient(135deg, #c53030 0%, #e53e3e 100%);
        color: white;
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(197, 48, 48, 0.3);
        color: white;
    }

    .btn-delete:active {
        transform: translateY(0);
    }

    .danger-zone {
        border: 2px solid #feb2b2;
        background: #fff5f5;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .danger-zone-title {
        color: #c53030;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .danger-zone-description {
        color: #742a2a;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .info-box {
        background: #ebf8ff;
        border-left: 4px solid #22004C;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box i {
        color: #22004C;
        margin-right: 0.5rem;
    }

    .info-box p {
        margin: 0;
        color: #2c5282;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .profile-header h1 {
            font-size: 1.5rem;
        }

        .profile-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <h1>
            <i class="fas fa-user-circle"></i>
            {{ trans('global.my_profile') }}
        </h1>
        <p>Kelola informasi akun dan keamanan Anda</p>
    </div>

    <div class="row">
        <!-- Update Profile Section -->
        <div class="col-md-6">
            <div class="profile-card">
                <div class="card-header-custom">
                    <i class="fas fa-user-edit"></i>
                    Informasi Profil
                </div>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Update informasi profil Anda seperti nama dan email</p>
                </div>
                <form method="POST" action="{{ route("frontend.profile.update") }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                        <input 
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', auth()->user()->name) }}" 
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                        <input 
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            placeholder="masukkan@email.anda"
                            required
                        >
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button class="btn-save" type="submit">
                            <i class="fas fa-save"></i>
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="col-md-6">
            <div class="profile-card">
                <div class="card-header-custom">
                    <i class="fas fa-lock"></i>
                    {{ trans('global.change_password') }}
                </div>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Pastikan menggunakan password yang kuat dan aman</p>
                </div>
                <form method="POST" action="{{ route("frontend.profile.password") }}">
                    @csrf
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label class="form-label required" for="password">Password Baru</label>
                        <input 
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Masukkan password baru"
                            required
                        >
                        @if($errors->has('password'))
                            <span class="help-block" role="alert">{{ $errors->first('password') }}</span>
                        @endif
                        <small style="color: #718096; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            <i class="fas fa-info-circle"></i> Minimal 8 karakter
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label required" for="password_confirmation">Konfirmasi Password Baru</label>
                        <input 
                            class="form-control" 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <button class="btn-save" type="submit">
                            <i class="fas fa-key"></i>
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Account Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="profile-card">
                <div class="card-header-custom" style="color: #c53030;">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ trans('global.delete_account') }}
                </div>
                <div class="danger-zone">
                    <div class="danger-zone-title">
                        <i class="fas fa-exclamation-circle"></i>
                        Zona Berbahaya
                    </div>
                    <p class="danger-zone-description">
                        Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
                    </p>
                    <form method="POST" action="{{ route("frontend.profile.destroy") }}" onsubmit="return prompt('{{ __('global.delete_account_warning') }}') == '{{ auth()->user()->email }}'">
                        @csrf
                        <button class="btn-delete" type="submit">
                            <i class="fas fa-trash-alt"></i>
                            {{ trans('global.delete') }} Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
