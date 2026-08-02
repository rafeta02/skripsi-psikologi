@extends('layouts.mahasiswa')

@section('title', 'Edit Pendaftaran Skripsi Reguler')

@php
    $selectedThemeIds = collect(old('theme_ids', $registration->themes->pluck('id')->all()));
    $isRevision = ($application->status ?? '') === 'revision';
@endphp

@push('styles')
<style>
    .wizard-container {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        padding: 2rem;
    }

    .wizard-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .wizard-header h2 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }

    .select2-container--bootstrap4 .select2-selection {
        border-radius: 8px !important;
        border: 1px solid #ced4da !important;
    }

    .select2-container--bootstrap4 .select2-selection--single {
        min-height: 38px !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple {
        min-height: 38px !important;
        padding: 4px 8px !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 4px !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        display: inline-flex !important;
        align-items: center !important;
        float: none !important;
        margin: 0 !important;
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 0.875rem !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        margin-right: 4px !important;
        border-right: none !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-search__field {
        margin-top: 0 !important;
        height: 28px !important;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 8px !important;
    }

    .select2-container--bootstrap4.select2-container--focus .select2-selection {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(34, 0, 76, 0.25) !important;
    }

    .select2-dropdown {
        border-radius: 8px !important;
        border-color: #ced4da !important;
        margin-top: 4px !important;
        z-index: 99999 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        background-color: white !important;
    }

    .select2-container--open,
    .select2-container--bootstrap4.select2-container--open {
        z-index: 99999 !important;
    }

    .wizard-container,
    .form-section,
    .card,
    .card-body,
    .container,
    .row,
    .col-md-12 {
        overflow: visible !important;
    }

    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e0e0e0;
        z-index: 0;
    }

    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .wizard-step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #999;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .wizard-step.active .wizard-step-number {
        background: var(--primary-color);
        color: white;
    }

    .wizard-step.completed .wizard-step-number {
        background: #28a745;
        color: white;
    }

    .wizard-step-title {
        font-size: 0.9rem;
        color: #666;
    }

    .wizard-step.active .wizard-step-title {
        color: var(--primary-color);
        font-weight: 600;
    }

    .form-section {
        display: none;
    }

    .form-section.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .wizard-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="wizard-container">
        <div class="wizard-header">
            <h2>
                <i class="fas fa-edit mr-2"></i>
                {{ $isRevision ? 'Revisi Pendaftaran Skripsi Reguler' : 'Edit Pendaftaran Skripsi Reguler' }}
            </h2>
            <p class="text-muted">
                @if($isRevision)
                    Perbaiki data sesuai catatan admin, lalu kirim ulang pendaftaran.
                @else
                    Perbarui data pendaftaran skripsi Anda
                @endif
            </p>

            @if($isRevision && !empty($registration->revision_notes))
                <div class="alert alert-warning text-left mt-3 mb-0">
                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Catatan revisi admin:</strong>
                    <p class="mb-0 mt-1">{!! nl2br(e($registration->revision_notes)) !!}</p>
                </div>
            @endif
        </div>

        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="wizard-step-number">1</div>
                <div class="wizard-step-title">Data Topik</div>
            </div>
            <div class="wizard-step" data-step="2">
                <div class="wizard-step-number">2</div>
                <div class="wizard-step-title">Data Dosen</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="wizard-step-number">3</div>
                <div class="wizard-step-title">Upload Dokumen</div>
            </div>
            <div class="wizard-step" data-step="4">
                <div class="wizard-step-number">4</div>
                <div class="wizard-step-title">Konfirmasi</div>
            </div>
        </div>

        <form action="{{ route('frontend.skripsi-registrations.update', $registration->id) }}" method="POST" enctype="multipart/form-data" id="skripsiForm">
            @csrf
            @method('PUT')

            <div class="form-section active" data-section="1">
                <h4 class="mb-4">Data Topik Skripsi</h4>

                <div class="form-group">
                    <label for="theme_ids">Tema Riset <span class="text-danger">*</span></label>
                    <select name="theme_ids[]" id="theme_ids" class="form-control" multiple required>
                        @foreach($keilmuans as $id => $name)
                            <option value="{{ $id }}" {{ $selectedThemeIds->contains($id) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Dapat memilih lebih dari satu tema riset</small>
                </div>

                <div class="form-group">
                    <label for="title">Judul Skripsi <span class="text-danger">*</span></label>
                    <textarea name="title" id="title" class="form-control" rows="3" required
                        placeholder="Tuliskan judul skripsi dalam Bahasa Indonesia">{{ old('title', $registration->title) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="title_en">Judul Skripsi (English)</label>
                    <textarea name="title_en" id="title_en" class="form-control" rows="3"
                        placeholder="Thesis title in English (optional)">{{ old('title_en', $registration->title_en) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="abstract">Abstrak / Ringkasan <span class="text-danger">*</span></label>
                    <textarea name="abstract" id="abstract" class="form-control" rows="6" required
                        placeholder="Jelaskan secara singkat latar belakang, tujuan, dan metode penelitian">{{ old('abstract', $registration->abstract) }}</textarea>
                </div>
            </div>

            <div class="form-section" data-section="2">
                <h4 class="mb-4">Data Dosen</h4>

                <div class="form-group">
                    <label for="tps_lecturer_id">Dosen TPS (Pembimbing Akademik)</label>
                    <select name="tps_lecturer_id" id="tps_lecturer_id" class="form-control">
                        <option value="">-- Pilih Dosen TPS --</option>
                        @foreach($dosens as $id => $name)
                            <option value="{{ $id }}" {{ (string) old('tps_lecturer_id', $registration->tps_lecturer_id) === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="preference_supervision_id">Preferensi Dosen Pembimbing</label>
                    <select name="preference_supervision_id" id="preference_supervision_id" class="form-control">
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                        @foreach($dosens as $id => $name)
                            <option value="{{ $id }}" {{ (string) old('preference_supervision_id', $registration->preference_supervision_id) === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilihan ini bersifat preferensi, penugasan akhir ditentukan oleh admin</small>
                </div>
            </div>

            <div class="form-section" data-section="3">
                <h4 class="mb-4">Upload Dokumen Persyaratan</h4>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Upload ulang hanya jika ingin mengganti dokumen. Format PDF, maksimal 5MB.
                </div>

                @if($registration->khs_all && count($registration->khs_all) > 0)
                    <div class="mb-3">
                        <label class="font-weight-bold d-block">KHS saat ini:</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($registration->khs_all as $key => $media)
                                <li class="mb-1">
                                    <i class="fas fa-file-pdf text-danger mr-1"></i>
                                    <a href="{{ $media->getUrl() }}" target="_blank">KHS File {{ $key + 1 }}</a>
                                    <small class="text-muted">({{ $media->file_name }})</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="khs_all">KHS Seluruh Semester</label>
                    <input type="file" name="khs_all[]" id="khs_all" class="form-control-file" multiple accept=".pdf">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti KHS</small>
                </div>

                @if($registration->krs_latest)
                    <div class="mb-3">
                        <label class="font-weight-bold d-block">KRS saat ini:</label>
                        <p class="mb-0">
                            <i class="fas fa-file-pdf text-danger mr-1"></i>
                            <a href="{{ $registration->krs_latest->getUrl() }}" target="_blank">{{ $registration->krs_latest->file_name }}</a>
                        </p>
                    </div>
                @endif

                <div class="form-group">
                    <label for="krs_latest">KRS Semester Terbaru</label>
                    <input type="file" name="krs_latest" id="krs_latest" class="form-control-file" accept=".pdf">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti KRS</small>
                </div>
            </div>

            <div class="form-section" data-section="4">
                <h4 class="mb-4">Konfirmasi Data</h4>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Pastikan semua data sudah benar sebelum {{ $isRevision ? 'mengirim ulang revisi' : 'menyimpan perubahan' }}.
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Data</h5>
                        <div id="summary-content">
                            <p class="text-muted">Silakan lengkapi form di step sebelumnya</p>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="agreement" required>
                        <label class="custom-control-label" for="agreement">
                            Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan
                        </label>
                    </div>
                </div>
            </div>

            <div class="wizard-actions">
                <div>
                    <a href="{{ route('frontend.skripsi-registrations.show', $registration->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="button" class="btn btn-secondary" id="btnPrev" style="display: none;">
                        <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" id="btnNext">
                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                        <i class="fas fa-check mr-2"></i>
                        {{ $isRevision ? 'Kirim Ulang Revisi' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    if (window.skripsiEditFormInitialized) {
        return;
    }
    window.skripsiEditFormInitialized = true;

    let currentStep = 1;
    const totalSteps = 4;
    const existingKhsCount = {{ $registration->khs_all ? count($registration->khs_all) : 0 }};
    const existingKrsName = @json($registration->krs_latest?->file_name);

    function showStep(step) {
        $('.form-section').removeClass('active').hide();
        $(`.form-section[data-section="${step}"]`).addClass('active').fadeIn(300);

        $('.wizard-step').removeClass('active completed');
        $('.wizard-step').each(function() {
            const stepNum = $(this).data('step');
            if (stepNum < step) {
                $(this).addClass('completed');
            } else if (stepNum === step) {
                $(this).addClass('active');
            }
        });

        $('#btnPrev').toggle(step > 1);
        $('#btnNext').toggle(step < totalSteps);
        $('#btnSubmit').toggle(step === totalSteps);

        if (step === totalSteps) {
            updateSummary();
        }

        currentStep = step;
        $('html, body').animate({ scrollTop: 0 }, 300);
    }

    function updateSummary() {
        const themes = ($('#theme_ids').select2('data') || [])
            .map(item => item.text)
            .filter(Boolean)
            .join(', ') || '-';
        const title = $('#title').val() || '-';
        const tpsLecturer = $('#tps_lecturer_id option:selected').text() || '-';
        const supervisor = $('#preference_supervision_id option:selected').text() || '-';
        const khsFiles = $('#khs_all')[0]?.files?.length || 0;
        const krsFile = $('#krs_latest')[0]?.files[0]?.name || existingKrsName || 'Tidak diubah';

        $('#summary-content').html(`
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tema Riset:</strong><br>${themes}</p>
                    <p><strong>Judul:</strong><br>${title}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Dosen TPS:</strong><br>${tpsLecturer}</p>
                    <p><strong>Preferensi Pembimbing:</strong><br>${supervisor}</p>
                </div>
                <div class="col-12">
                    <p><strong>Dokumen:</strong></p>
                    <ul>
                        <li>KHS: ${khsFiles > 0 ? khsFiles + ' file baru' : existingKhsCount + ' file (tidak diubah)'}</li>
                        <li>KRS: ${krsFile}</li>
                    </ul>
                </div>
            </div>
        `);
    }

    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#theme_ids').select2({
                placeholder: '-- Pilih Tema Riset --',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                closeOnSelect: true
            });

            $('#tps_lecturer_id, #preference_supervision_id').select2({
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body')
            });
        }

        $('#btnNext').on('click', function(e) {
            e.preventDefault();
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });

        $('#btnPrev').on('click', function(e) {
            e.preventDefault();
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });

        $('#skripsiForm').on('submit', function(e) {
            if (!$('#agreement').is(':checked')) {
                e.preventDefault();
                alert('Anda harus menyetujui pernyataan di atas sebelum submit!');
            }
        });

        showStep(1);
    });
})();
</script>
@endpush
