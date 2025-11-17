@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <h2>Detail Hasil Seminar</h2>
                        <p>Informasi lengkap hasil seminar proposal</p>
                    </div>
                    <div>
                        @if($applicationResultSeminar->result == 'passed')
                            <span class="status-badge approved large">
                                <i class="fas fa-check-circle mr-2"></i> Lulus
                            </span>
                        @elseif($applicationResultSeminar->result == 'revision')
                            <span class="status-badge pending large">
                                <i class="fas fa-edit mr-2"></i> Revisi
                            </span>
                        @elseif($applicationResultSeminar->result == 'failed')
                            <span class="status-badge rejected large">
                                <i class="fas fa-times-circle mr-2"></i> Tidak Lulus
                            </span>
                        @endif
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Informasi Aplikasi -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-file-alt mr-2"></i> Informasi Aplikasi
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Mahasiswa:</span>
                                    <span class="info-value">{{ $applicationResultSeminar->application->mahasiswa->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">NIM:</span>
                                    <span class="info-value">{{ $applicationResultSeminar->application->mahasiswa->nim ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Seminar -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-clipboard-check mr-2"></i> Hasil Seminar
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Hasil:</span>
                                    <span class="info-value">
                                        {{ App\Models\ApplicationResultSeminar::RESULT_SELECT[$applicationResultSeminar->result] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            @if($applicationResultSeminar->revision_deadline)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Batas Revisi:</span>
                                        <span class="info-value">
                                            <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($applicationResultSeminar->revision_deadline)->format('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($applicationResultSeminar->note)
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-sticky-note mr-2"></i> Catatan & Saran Perbaikan
                            </h3>
                            <div class="note-box">
                                {!! nl2br(e($applicationResultSeminar->note)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Dokumen -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-folder-open mr-2"></i> Dokumen
                        </h3>

                        @if($applicationResultSeminar->report_document && count($applicationResultSeminar->report_document) > 0)
                            <h4 class="document-category-title">Berita Acara Seminar</h4>
                            <div class="document-list">
                                @foreach($applicationResultSeminar->report_document as $key => $media)
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $media['file_name'] ?? 'Berita Acara ' . ($key + 1) }}</div>
                                            <div class="document-meta">
                                                <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($media['size'] ?? 0) / 1024, 2) }} KB</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($applicationResultSeminar->attendance_document)
                            <h4 class="document-category-title">Daftar Hadir</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultSeminar->attendance_document['file_name'] ?? 'Daftar Hadir' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultSeminar->attendance_document['size'] ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ $applicationResultSeminar->attendance_document['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $applicationResultSeminar->attendance_document['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($applicationResultSeminar->form_document && count($applicationResultSeminar->form_document) > 0)
                            <h4 class="document-category-title">Form Penilaian</h4>
                            <div class="document-list">
                                @foreach($applicationResultSeminar->form_document as $key => $media)
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $media['file_name'] ?? 'Form Penilaian ' . ($key + 1) }}</div>
                                            <div class="document-meta">
                                                <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($media['size'] ?? 0) / 1024, 2) }} KB</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($applicationResultSeminar->latest_script)
                            <h4 class="document-category-title">Naskah Proposal Terbaru</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultSeminar->latest_script['file_name'] ?? 'Naskah Proposal' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultSeminar->latest_script['size'] ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ $applicationResultSeminar->latest_script['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $applicationResultSeminar->latest_script['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($applicationResultSeminar->documentation && count($applicationResultSeminar->documentation) > 0)
                            <h4 class="document-category-title">Dokumentasi</h4>
                            <div class="document-list">
                                @foreach($applicationResultSeminar->documentation as $key => $media)
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $media['file_name'] ?? 'Dokumentasi ' . ($key + 1) }}</div>
                                            <div class="document-meta">
                                                <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($media['size'] ?? 0) / 1024, 2) }} KB</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('application_result_seminar_edit')
                        <a href="{{ route('frontend.application-result-seminars.edit', $applicationResultSeminar->id) }}" class="btn-primary-custom">
                            <i class="fas fa-edit mr-2"></i> Edit Data
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection