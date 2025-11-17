@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <h2>Detail Seminar Proposal</h2>
                        <p>Informasi lengkap pendaftaran seminar proposal skripsi</p>
                    </div>
                    <div>
                        @if($skripsiSeminar->application->status == 'submitted')
                            <span class="status-badge pending large">
                                <i class="fas fa-clock mr-2"></i> Menunggu Verifikasi
                            </span>
                        @elseif($skripsiSeminar->application->status == 'approved')
                            <span class="status-badge approved large">
                                <i class="fas fa-check-circle mr-2"></i> Disetujui
                            </span>
                        @elseif($skripsiSeminar->application->status == 'rejected')
                            <span class="status-badge rejected large">
                                <i class="fas fa-times-circle mr-2"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Informasi Mahasiswa -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-user mr-2"></i> Informasi Mahasiswa
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nama:</span>
                                    <span class="info-value">{{ $skripsiSeminar->application->mahasiswa->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">NIM:</span>
                                    <span class="info-value">{{ $skripsiSeminar->application->mahasiswa->nim ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Tanggal Daftar:</span>
                                    <span class="info-value">
                                        <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                        {{ $skripsiSeminar->created_at ? $skripsiSeminar->created_at->format('d F Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Proposal -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-book mr-2"></i> Informasi Proposal
                        </h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-item">
                                    <span class="info-label">Judul Proposal:</span>
                                    <span class="info-value">{{ $skripsiSeminar->title ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-folder-open mr-2"></i> Dokumen
                        </h3>

                        @if($skripsiSeminar->proposal_document)
                            <h4 class="document-category-title">Dokumen Proposal</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $skripsiSeminar->proposal_document['file_name'] ?? 'Dokumen Proposal' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($skripsiSeminar->proposal_document['size'] ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ $skripsiSeminar->proposal_document['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $skripsiSeminar->proposal_document['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiSeminar->approval_document)
                            <h4 class="document-category-title">Form Persetujuan</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-signature"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $skripsiSeminar->approval_document['file_name'] ?? 'Form Persetujuan' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($skripsiSeminar->approval_document['size'] ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ $skripsiSeminar->approval_document['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $skripsiSeminar->approval_document['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiSeminar->plagiarism_document)
                            <h4 class="document-category-title">Hasil Cek Plagiarisme</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $skripsiSeminar->plagiarism_document['file_name'] ?? 'Hasil Cek Plagiarisme' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($skripsiSeminar->plagiarism_document['size'] ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ $skripsiSeminar->plagiarism_document['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $skripsiSeminar->plagiarism_document['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('frontend.skripsi-seminars.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('skripsi_seminar_edit')
                        @if($skripsiSeminar->application->status != 'approved')
                            <a href="{{ route('frontend.skripsi-seminars.edit', $skripsiSeminar->id) }}" class="btn-primary-custom">
                                <i class="fas fa-edit mr-2"></i> Edit Data
                            </a>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection