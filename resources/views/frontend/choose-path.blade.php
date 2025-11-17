@extends('layouts.frontend')

@section('content')
<style>
    .choose-path-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    
    .path-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .path-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }
    
    .path-header p {
        font-size: 1.1rem;
        color: #718096;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .path-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2.5rem;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .path-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
        position: relative;
    }
    
    .path-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(34, 0, 76, 0.15);
        text-decoration: none;
    }
    
    .path-card-header {
        padding: 2.5rem 2rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .path-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .path-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .path-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .path-subtitle {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .path-card-body {
        padding: 2rem;
    }
    
    .path-description {
        color: #4a5568;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    
    .path-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem;
    }
    
    .path-features li {
        padding: 0.75rem 0;
        color: #2d3748;
        font-size: 0.95rem;
        display: flex;
        align-items: flex-start;
    }
    
    .path-features li::before {
        content: '✓';
        display: inline-block;
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 24px;
        margin-right: 0.75rem;
        font-weight: bold;
        flex-shrink: 0;
        font-size: 0.85rem;
    }
    
    .path-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #ebf8ff;
        color: #2c5282;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .path-card.recommended .path-badge {
        background: #fef3c7;
        color: #92400e;
    }
    
    .path-card.recommended::after {
        content: 'Rekomendasi';
        position: absolute;
        top: 20px;
        right: -30px;
        background: #fbbf24;
        color: #78350f;
        padding: 0.35rem 2.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        transform: rotate(45deg);
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
    }
    
    .path-button {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .path-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 0, 76, 0.3);
    }
    
    .path-button i {
        margin-left: 0.5rem;
        transition: transform 0.3s;
    }
    
    .path-card:hover .path-button i {
        transform: translateX(5px);
    }
    
    .info-box {
        background: #ebf8ff;
        border-left: 4px solid #4299e1;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 3rem;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .info-box-title {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .info-box-text {
        color: #2d3748;
        margin: 0;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .path-cards {
            grid-template-columns: 1fr;
        }
        
        .path-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="choose-path-container">
    <div class="container">
        <!-- Header -->
        <div class="path-header">
            <h1>Pilih Jalur Skripsi Anda</h1>
            <p>Pilih jalur yang sesuai dengan rencana studi dan minat penelitian Anda</p>
        </div>

        <!-- Path Cards -->
        <div class="path-cards">
            <!-- Skripsi Reguler -->
            <a href="{{ route('frontend.skripsi-registrations.create') }}" class="path-card recommended">
                <div class="path-card-header">
                    <div class="path-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2 class="path-title">Skripsi Reguler</h2>
                    <p class="path-subtitle">Jalur penelitian akademik standar</p>
                </div>
                <div class="path-card-body">
                    <span class="path-badge">Paling Populer</span>
                    
                    <p class="path-description">
                        Jalur skripsi standar dengan fokus pada penelitian akademik individual. Cocok untuk mahasiswa yang ingin mendalami topik penelitian secara mendalam dengan bimbingan dosen.
                    </p>
                    
                    <ul class="path-features">
                        <li>Penelitian individual dengan bimbingan dosen</li>
                        <li>Topik penelitian akademik murni</li>
                        <li>Proses: Pendaftaran → Seminar Proposal → Sidang</li>
                        <li>Durasi standar 1-2 semester</li>
                        <li>Fokus pada metodologi penelitian akademik</li>
                    </ul>
                    
                    <div class="path-button">
                        Pilih Skripsi Reguler
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- MBKM -->
            <a href="{{ route('frontend.mbkm-registrations.create') }}" class="path-card">
                <div class="path-card-header">
                    <div class="path-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="path-title">Skripsi MBKM</h2>
                    <p class="path-subtitle">Merdeka Belajar Kampus Merdeka</p>
                </div>
                <div class="path-card-body">
                    <span class="path-badge">Program Khusus</span>
                    
                    <p class="path-description">
                        Jalur skripsi berbasis program MBKM dengan penelitian kolaboratif dalam kelompok. Cocok untuk mahasiswa yang telah mengikuti program MBKM dan ingin mengintegrasikan pengalaman tersebut.
                    </p>
                    
                    <ul class="path-features">
                        <li>Penelitian kolaboratif dalam kelompok</li>
                        <li>Integrasi dengan pengalaman MBKM</li>
                        <li>Proses: Pendaftaran Kelompok → Seminar → Sidang</li>
                        <li>Memerlukan persetujuan kelompok dan dosen</li>
                        <li>Fokus pada aplikasi praktis dan kolaborasi</li>
                    </ul>
                    
                    <div class="path-button">
                        Pilih Skripsi MBKM
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i> Perlu Bantuan Memilih?
            </div>
            <div class="info-box-text">
                <strong>Skripsi Reguler</strong> direkomendasikan untuk sebagian besar mahasiswa yang ingin fokus pada penelitian akademik individual. 
                <strong>Skripsi MBKM</strong> cocok untuk mahasiswa yang telah mengikuti program MBKM dan ingin melanjutkan penelitian berbasis pengalaman tersebut dalam kelompok.
                <br><br>
                Jika masih ragu, silakan konsultasikan dengan dosen pembimbing akademik atau koordinator skripsi program studi Anda.
            </div>
        </div>
    </div>
</div>
@endsection
