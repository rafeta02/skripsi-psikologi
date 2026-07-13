@extends('layouts.public')

@section('title', 'Selamat Datang')

@push('styles')
<style>
    .hero-section {
        padding: 5rem 0;
        text-align: center;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .hero-image {
        max-width: 100%;
        height: auto;
        margin-top: 2rem;
    }
    
    .feature-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
        font-size: 3rem;
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }
    
    .feature-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .feature-description {
        color: #6c757d;
        line-height: 1.6;
    }
    
    .info-section {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        margin: 3rem 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .info-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .process-flow {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .process-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid var(--secondary-color);
    }
    
    .process-number {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .cta-section {
        text-align: center;
        padding: 3rem 0;
    }
    
    .btn-cta {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 1rem 3rem;
        border-radius: 30px;
        font-size: 1.2rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(34, 0, 76, 0.3);
        color: white;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">
            <i class="fas fa-graduation-cap"></i> Sistem Skripsi
        </h1>
        <p class="hero-subtitle">
            Fakultas Psikologi - Universitas Sebelas Maret
        </p>
        <p class="lead">
            Platform terintegrasi untuk pengelolaan Skripsi Reguler dan Skripsi MBKM
        </p>
    </div>

    <!-- Features -->
    <div class="row">
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="feature-title">Skripsi Reguler</h3>
                <p class="feature-description">
                    Alur pendaftaran dan pengelolaan skripsi reguler dengan proses review proposal individual oleh reviewer.
                </p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Skripsi MBKM</h3>
                <p class="feature-description">
                    Jalur skripsi dengan program MBKM yang mencakup seminar formal dan pengerjaan kelompok.
                </p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="feature-title">Monitoring Progres</h3>
                <p class="feature-description">
                    Pantau progres skripsi Anda secara real-time dari pendaftaran hingga sidang akhir.
                </p>
            </div>
        </div>
    </div>

    <!-- Info Section - Skripsi Reguler -->
    <div class="info-section">
        <h2 class="info-title">
            <i class="fas fa-route mr-2"></i> Alur Skripsi Reguler
        </h2>
        <div class="process-flow">
            <div class="process-item">
                <span class="process-number">1</span>
                <div>
                    <strong>Pendaftaran Skripsi</strong>
                    <p class="mb-0 small text-muted">Daftar aplikasi skripsi dengan upload dokumen persyaratan</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">2</span>
                <div>
                    <strong>Penugasan Pembimbing</strong>
                    <p class="mb-0 small text-muted">Admin menugaskan dosen pembimbing</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">3</span>
                <div>
                    <strong>Review Proposal</strong>
                    <p class="mb-0 small text-muted">Proposal direview oleh reviewer yang ditugaskan</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">4</span>
                <div>
                    <strong>Proses Penelitian</strong>
                    <p class="mb-0 small text-muted">Melaksanakan penelitian dengan bimbingan</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">5</span>
                <div>
                    <strong>Sidang Skripsi</strong>
                    <p class="mb-0 small text-muted">Pendaftaran dan pelaksanaan sidang akhir</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section - MBKM -->
    <div class="info-section">
        <h2 class="info-title">
            <i class="fas fa-project-diagram mr-2"></i> Alur Skripsi MBKM
        </h2>
        <div class="process-flow">
            <div class="process-item">
                <span class="process-number">1</span>
                <div>
                    <strong>Pendaftaran MBKM</strong>
                    <p class="mb-0 small text-muted">Daftar MBKM dengan pilih research group dan pembimbing</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">2</span>
                <div>
                    <strong>Validasi Pembimbing</strong>
                    <p class="mb-0 small text-muted">Dosen menerima atau menolak penugasan</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">3</span>
                <div>
                    <strong>Review Kelayakan Proposal</strong>
                    <p class="mb-0 small text-muted">Pendaftaran dan penjadwalan seminar formal</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">4</span>
                <div>
                    <strong>Proses Penelitian</strong>
                    <p class="mb-0 small text-muted">Melaksanakan penelitian dengan bimbingan</p>
                </div>
            </div>
            <div class="process-item">
                <span class="process-number">5</span>
                <div>
                    <strong>Sidang Skripsi</strong>
                    <p class="mb-0 small text-muted">Pendaftaran dan pelaksanaan sidang akhir</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        @guest
            <h3 class="mb-4">Siap Memulai Skripsi Anda?</h3>
            <a href="{{ route('login') }}" class="btn btn-cta">
                <i class="fas fa-sign-in-alt mr-2"></i> Login Sekarang
            </a>
        @else
            <h3 class="mb-4">Selamat Datang Kembali!</h3>
            @if(Auth::user()->level == 'MAHASISWA')
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-cta">
                    <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard Mahasiswa
                </a>
            @elseif(Auth::user()->level == 'DOSEN')
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-cta">
                    <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard Dosen
                </a>
            @else
                <a href="{{ route('admin.home') }}" class="btn btn-cta">
                    <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard Admin
                </a>
            @endif
        @endguest
    </div>
</div>
@endsection
