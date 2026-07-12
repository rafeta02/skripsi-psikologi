@extends('layouts.mahasiswa')

@section('title', 'Pilih Jalur Skripsi')

@push('styles')
<style>
    .path-selection-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    
    .path-card {
        background: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 3px solid transparent;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .path-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .path-card:hover::before {
        transform: scaleX(1);
    }
    
    .path-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(34, 0, 76, 0.2);
        border-color: var(--primary-color);
    }
    
    .path-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8f4ff 0%, #ffffff 100%);
    }
    
    .path-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: white;
        transition: all 0.4s ease;
    }
    
    .path-card:hover .path-icon {
        transform: rotate(10deg) scale(1.1);
    }
    
    .path-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .path-description {
        color: #6c757d;
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 2rem;
    }
    
    .path-features {
        text-align: left;
        margin: 2rem 0;
    }
    
    .path-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .path-feature-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .path-feature-icon {
        color: var(--primary-color);
        font-size: 1.2rem;
        margin-top: 2px;
    }
    
    .btn-select-path {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        color: white;
        padding: 1rem 3rem;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-select-path:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(34, 0, 76, 0.3);
        color: white;
    }

    .path-select-hint {
        display: inline-block;
        margin-top: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color, #4A0080) 100%);
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .path-card:hover .path-select-hint {
        box-shadow: 0 8px 20px rgba(34, 0, 76, 0.25);
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .page-header h1 {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .page-header p {
        font-size: 1.2rem;
        color: #6c757d;
    }
    
    .info-alert {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-left: 5px solid var(--primary-color);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .radio-input {
        display: none;
    }
    
    .radio-input:checked + .path-card {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8f4ff 0%, #ffffff 100%);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-route"></i> Pilih Jalur Skripsi</h1>
        <p>Tentukan jalur skripsi yang sesuai dengan rencana akademik Anda</p>
    </div>
    
    <div class="info-alert">
        <div class="d-flex align-items-start">
            <i class="fas fa-info-circle fa-2x text-primary mr-3"></i>
            <div>
                <h5 class="font-weight-bold text-primary mb-2">Informasi Penting</h5>
                <p class="mb-0">Klik salah satu kartu di bawah untuk memilih jalur skripsi. Anda akan langsung diarahkan ke form pendaftaran.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('frontend.choose-path.store') }}" method="POST" id="pathSelectionForm">
        @csrf
        <div class="row">
            <!-- Skripsi Reguler -->
            <div class="col-md-6 mb-4">
                <input type="radio" name="path_type" value="skripsi" id="path_skripsi" class="radio-input" required>
                <label for="path_skripsi" class="path-card w-100">
                    <div class="path-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h2 class="path-title">Skripsi Reguler</h2>
                    <p class="path-description">
                        Jalur skripsi standar dengan proses review proposal individual oleh reviewer yang ditugaskan.
                    </p>
                    
                    <div class="path-features">
                        <div class="path-feature-item">
                            <i class="fas fa-check-circle path-feature-icon"></i>
                            <div>
                                <strong>Pendaftaran Skripsi</strong>
                                <p class="mb-0 small text-muted">Upload dokumen dan pilih topik penelitian</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-user-tie path-feature-icon"></i>
                            <div>
                                <strong>Penugasan Pembimbing</strong>
                                <p class="mb-0 small text-muted">Admin menugaskan dosen pembimbing</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-search path-feature-icon"></i>
                            <div>
                                <strong>Review Proposal</strong>
                                <p class="mb-0 small text-muted">Reviewer menilai proposal secara individual (tidak ada seminar formal)</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-microscope path-feature-icon"></i>
                            <div>
                                <strong>Proses Penelitian</strong>
                                <p class="mb-0 small text-muted">Melaksanakan penelitian dengan bimbingan</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-graduation-cap path-feature-icon"></i>
                            <div>
                                <strong>Sidang Skripsi</strong>
                                <p class="mb-0 small text-muted">Pendaftaran dan pelaksanaan sidang akhir</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="path-select-hint">
                        <i class="fas fa-mouse-pointer mr-1"></i> Klik kartu ini untuk memilih
                    </div>
                </label>
            </div>
            
            <!-- Skripsi MBKM -->
            <div class="col-md-6 mb-4">
                <input type="radio" name="path_type" value="mbkm" id="path_mbkm" class="radio-input" required>
                <label for="path_mbkm" class="path-card w-100">
                    <div class="path-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="path-title">Skripsi MBKM</h2>
                    <p class="path-description">
                        Program Merdeka Belajar Kampus Merdeka dengan seminar formal dan pengerjaan kelompok.
                    </p>
                    
                    <div class="path-features">
                        <div class="path-feature-item">
                            <i class="fas fa-check-circle path-feature-icon"></i>
                            <div>
                                <strong>Pendaftaran MBKM</strong>
                                <p class="mb-0 small text-muted">Pilih research group dan dosen pembimbing</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-user-check path-feature-icon"></i>
                            <div>
                                <strong>Validasi Pembimbing</strong>
                                <p class="mb-0 small text-muted">Dosen menerima atau menolak penugasan</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-presentation path-feature-icon"></i>
                            <div>
                                <strong>Seminar MBKM</strong>
                                <p class="mb-0 small text-muted">Pendaftaran dan penjadwalan seminar formal dengan reviewer</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-project-diagram path-feature-icon"></i>
                            <div>
                                <strong>Proses Penelitian</strong>
                                <p class="mb-0 small text-muted">Melaksanakan penelitian dengan bimbingan</p>
                            </div>
                        </div>
                        <div class="path-feature-item">
                            <i class="fas fa-award path-feature-icon"></i>
                            <div>
                                <strong>Sidang Skripsi</strong>
                                <p class="mb-0 small text-muted">Pendaftaran dan pelaksanaan sidang akhir</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="path-select-hint">
                        <i class="fas fa-mouse-pointer mr-1"></i> Klik kartu ini untuk memilih
                    </div>
                </label>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Klik kartu → pilih jalur dan langsung lanjut
        $('input[name="path_type"]').on('change', function() {
            $('.path-card').removeClass('selected');
            $('label[for="' + this.id + '"]').addClass('selected');
            $('#loadingSpinner').show();
            $('#pathSelectionForm').submit();
        });
    });
</script>
@endpush
