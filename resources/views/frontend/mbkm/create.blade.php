@extends('layouts.mahasiswa')

@section('title', 'Pendaftaran MBKM')

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
    
    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 8%;
        right: 8%;
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
        font-size: 0.85rem;
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
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .wizard-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }
    
    /* Select2 Bootstrap4 Theme Customization */
    .select2-container--bootstrap4 .select2-selection {
        border-radius: 8px !important;
        border: 1px solid #ced4da !important;
        min-height: 38px !important;
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
    
    .select2-container--bootstrap4 .select2-results__option {
        padding: 10px 16px !important;
    }
    
    .select2-container--bootstrap4 .select2-results__option--highlighted {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    
    /* Dropdown dengan z-index sangat tinggi */
    .select2-dropdown {
        border-radius: 8px !important;
        border-color: #ced4da !important;
        margin-top: 4px !important;
        z-index: 99999 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        background-color: white !important;
    }
    
    .select2-container {
        z-index: 10 !important;
    }
    
    .select2-container--open {
        z-index: 99999 !important;
    }
    
    /* Pastikan dropdown container tidak di-clip */
    .select2-container--bootstrap4.select2-container--open {
        z-index: 99999 !important;
    }
    
    .select2-results__options {
        max-height: 300px !important;
        overflow-y: auto !important;
    }
    
    .select2-search--dropdown {
        padding: 8px !important;
    }
    
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--primary-color) !important;
        outline: none !important;
    }
    
    /* Remove any clipping from parent elements */
    .wizard-container,
    .form-section,
    .card,
    .card-body,
    .container,
    .row,
    .col-md-12 {
        overflow: visible !important;
    }
    
    /* Ensure body can contain dropdown */
    body {
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="wizard-container">
        <div class="wizard-header">
            <h2><i class="fas fa-users mr-2"></i> Pendaftaran Skripsi MBKM</h2>
            <p class="text-muted">Lengkapi form pendaftaran MBKM dengan data yang akurat</p>
        </div>
        
        <!-- Wizard Steps -->
        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="wizard-step-number">1</div>
                <div class="wizard-step-title">Research Group</div>
            </div>
            <div class="wizard-step" data-step="2">
                <div class="wizard-step-number">2</div>
                <div class="wizard-step-title">Data Topik</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="wizard-step-number">3</div>
                <div class="wizard-step-title">Nilai</div>
            </div>
            <div class="wizard-step" data-step="4">
                <div class="wizard-step-number">4</div>
                <div class="wizard-step-title">Dokumen</div>
            </div>
            <div class="wizard-step" data-step="5">
                <div class="wizard-step-number">5</div>
                <div class="wizard-step-title">Konfirmasi</div>
            </div>
        </div>
        
        <form action="{{ route('frontend.mbkm.store', $application->id) }}" method="POST" enctype="multipart/form-data" id="mbkmForm">
            @csrf
            
            <!-- Step 1: Research Group & Pembimbing -->
            <div class="form-section active" data-section="1">
                <h4 class="mb-4">Research Group & Dosen Pembimbing</h4>
                
                <div class="form-group">
                    <label for="research_group_id">Research Group <span class="text-danger">*</span></label>
                    <select name="research_group_id" id="research_group_id" class="form-control" required>
                        <option value="">-- Pilih Research Group --</option>
                        @foreach($researchGroups as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="preference_supervision_id">Pilih Dosen Pembimbing <span class="text-danger">*</span></label>
                    <select name="preference_supervision_id" id="preference_supervision_id" class="form-control" required>
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                        @foreach($dosens as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Dosen ini akan menilai dan menerima/menolak penugasan Anda</small>
                </div>
            </div>
            
            <!-- Step 2: Data Topik -->
            <div class="form-section" data-section="2">
                <h4 class="mb-4">Data Topik MBKM & Skripsi</h4>
                
                <div class="form-group">
                    <label for="theme_id">Bidang Keilmuan <span class="text-danger">*</span></label>
                    <select name="theme_id" id="theme_id" class="form-control" required>
                        <option value="">-- Pilih Bidang Keilmuan --</option>
                        @foreach($keilmuans as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="title_mbkm">Judul Kegiatan MBKM <span class="text-danger">*</span></label>
                    <textarea name="title_mbkm" id="title_mbkm" class="form-control" rows="2" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="title">Judul Skripsi <span class="text-danger">*</span></label>
                    <textarea name="title" id="title" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="note">Catatan Tambahan</label>
                    <textarea name="note" id="note" class="form-control" rows="3"
                        placeholder="Masukkan catatan atau keterangan tambahan jika ada"></textarea>
                </div>
            </div>
            
            <!-- Step 3: Nilai-nilai -->
            <div class="form-section" data-section="3">
                <h4 class="mb-4">Data Nilai</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_sks_taken">Total SKS yang Ditempuh <span class="text-danger">*</span></label>
                            <input type="number" name="total_sks_taken" id="total_sks_taken" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sks_mkp_taken">SKS MKP yang Ditempuh <span class="text-danger">*</span></label>
                            <input type="number" name="sks_mkp_taken" id="sks_mkp_taken" class="form-control" required min="0">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_kuantitatif">Nilai MK Kuantitatif <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_kuantitatif" id="nilai_mk_kuantitatif" class="form-control" required maxlength="10">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_kualitatif">Nilai MK Kualitatif <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_kualitatif" id="nilai_mk_kualitatif" class="form-control" required maxlength="10">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_statistika_dasar">Nilai MK Statistika Dasar <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_statistika_dasar" id="nilai_mk_statistika_dasar" class="form-control" required maxlength="10">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_statistika_lanjutan">Nilai MK Statistika Lanjutan <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_statistika_lanjutan" id="nilai_mk_statistika_lanjutan" class="form-control" required maxlength="10">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_konstruksi_tes">Nilai MK Konstruksi Tes <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_konstruksi_tes" id="nilai_mk_konstruksi_tes" class="form-control" required maxlength="10">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_mk_tps">Nilai MK TPS <span class="text-danger">*</span></label>
                            <input type="text" name="nilai_mk_tps" id="nilai_mk_tps" class="form-control" required maxlength="10">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 4: Upload Dokumen -->
            <div class="form-section" data-section="4">
                <h4 class="mb-4">Upload Dokumen Persyaratan</h4>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Semua dokumen harus dalam format PDF
                </div>
                
                <div class="form-group">
                    <label for="khs_all">KHS Seluruh Semester <span class="text-danger">*</span></label>
                    <input type="file" name="khs_all[]" id="khs_all" class="form-control-file" multiple required accept=".pdf">
                    <small class="form-text text-muted">Max 5MB per file</small>
                </div>
                
                <div class="form-group">
                    <label for="krs_latest">KRS Semester Terbaru <span class="text-danger">*</span></label>
                    <input type="file" name="krs_latest" id="krs_latest" class="form-control-file" required accept=".pdf">
                    <small class="form-text text-muted">Max 5MB</small>
                </div>
                
                <div class="form-group">
                    <label for="spp">Bukti Pembayaran SPP <span class="text-danger">*</span></label>
                    <input type="file" name="spp" id="spp" class="form-control-file" required accept=".pdf">
                    <small class="form-text text-muted">Max 5MB</small>
                </div>
                
                <div class="form-group">
                    <label for="proposal_mbkm">Proposal MBKM <span class="text-danger">*</span></label>
                    <input type="file" name="proposal_mbkm" id="proposal_mbkm" class="form-control-file" required accept=".pdf">
                    <small class="form-text text-muted">Max 10MB</small>
                </div>
                
                <div class="form-group">
                    <label for="recognition_form">Form Rekognisi (Opsional)</label>
                    <input type="file" name="recognition_form" id="recognition_form" class="form-control-file" accept=".pdf">
                    <small class="form-text text-muted">Max 5MB</small>
                </div>
            </div>
            
            <!-- Step 5: Konfirmasi -->
            <div class="form-section" data-section="5">
                <h4 class="mb-4">Konfirmasi Data</h4>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Pastikan semua data yang Anda masukkan sudah benar. Setelah submit, data akan menunggu verifikasi dari admin dan persetujuan dosen pembimbing.
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
            
            <!-- Wizard Actions -->
            <div class="wizard-actions">
                <button type="button" class="btn btn-secondary" id="btnPrev" style="display: none;">
                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                </button>
                <div></div>
                <button type="button" class="btn btn-primary" id="btnNext">
                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                </button>
                <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                    <i class="fas fa-check mr-2"></i> Submit Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Prevent multiple initialization
    if (window.mbkmFormInitialized) {
        console.warn('MBKM form already initialized');
        return;
    }
    window.mbkmFormInitialized = true;
    
    let currentStep = 1;
    const totalSteps = 5;
    
    console.log('[MBKM Wizard] Initializing...');
    
    function showStep(step) {
        console.log('[MBKM Wizard] Showing step:', step);
        
        // Update form sections
        $('.form-section').removeClass('active').hide();
        $(`.form-section[data-section="${step}"]`).addClass('active').fadeIn(300);
        
        // Update wizard steps
        $('.wizard-step').removeClass('active').removeClass('completed');
        $('.wizard-step').each(function() {
            const stepNum = $(this).data('step');
            if (stepNum < step) {
                $(this).addClass('completed');
            } else if (stepNum === step) {
                $(this).addClass('active');
            }
        });
        
        // Update buttons
        if (step === 1) {
            $('#btnPrev').hide();
        } else {
            $('#btnPrev').show();
        }
        
        if (step === totalSteps) {
            $('#btnNext').hide();
            $('#btnSubmit').show();
            updateSummary();
        } else {
            $('#btnNext').show();
            $('#btnSubmit').hide();
        }
        
        currentStep = step;
        
        // Scroll to top smoothly
        $('html, body').animate({ scrollTop: 0 }, 300);
    }
    
    function updateSummary() {
        const researchGroup = $('#research_group_id option:selected').text() || '-';
        const supervisor = $('#preference_supervision_id option:selected').text() || '-';
        const theme = $('#theme_id option:selected').text() || '-';
        const titleMbkm = $('#title_mbkm').val() || '-';
        const title = $('#title').val() || '-';
        const totalSks = $('#total_sks_taken').val() || '-';
        
        let summary = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Research Group:</strong><br>${researchGroup}</p>
                    <p><strong>Dosen Pembimbing:</strong><br>${supervisor}</p>
                    <p><strong>Bidang Keilmuan:</strong><br>${theme}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Judul MBKM:</strong><br>${titleMbkm}</p>
                    <p><strong>Judul Skripsi:</strong><br>${title}</p>
                    <p><strong>Total SKS:</strong><br>${totalSks}</p>
                </div>
            </div>
        `;
        
        $('#summary-content').html(summary);
    }
    
    // Wait for document ready
    $(document).ready(function() {
        console.log('[MBKM Wizard] Document ready');
        console.log('[MBKM Wizard] jQuery version:', $.fn.jquery);
        
        // Initialize Select2 for all select elements
        if (typeof $.fn.select2 !== 'undefined') {
            console.log('[MBKM Wizard] Initializing Select2...');
            
            $('#research_group_id').select2({
                placeholder: '-- Pilih Research Group --',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                dropdownAutoWidth: false
            });
            
            $('#preference_supervision_id').select2({
                placeholder: '-- Pilih Dosen Pembimbing --',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                dropdownAutoWidth: false
            });
            
            $('#theme_id').select2({
                placeholder: '-- Pilih Bidang Keilmuan --',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                dropdownAutoWidth: false
            });
            
            console.log('[MBKM Wizard] Select2 initialized');
        } else {
            console.warn('[MBKM Wizard] Select2 library not found');
        }
        
        // File input styling and validation
        $('input[type="file"]').on('change', function() {
            const fileInput = $(this);
            const files = fileInput[0].files;
            const label = fileInput.siblings('.custom-file-label');
            
            if (files.length > 0) {
                if (files.length === 1) {
                    label.text(files[0].name);
                } else {
                    label.text(files.length + ' file(s) dipilih');
                }
            } else {
                label.text('Pilih file...');
            }
        });
        
        // Check if elements exist
        const btnNext = $('#btnNext');
        const btnPrev = $('#btnPrev');
        const btnSubmit = $('#btnSubmit');
        const form = $('#mbkmForm');
        
        console.log('[MBKM Wizard] Elements found:', {
            btnNext: btnNext.length,
            btnPrev: btnPrev.length,
            btnSubmit: btnSubmit.length,
            form: form.length
        });
        
        if (btnNext.length === 0) {
            console.error('[MBKM Wizard] ERROR: btnNext not found!');
            return;
        }
        
        // Remove any existing event handlers
        btnNext.off('click');
        btnPrev.off('click');
        form.off('submit');
        
        // Next button
        btnNext.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('[MBKM Wizard] Next clicked, step:', currentStep);
            
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
            
            return false;
        });
        
        // Previous button
        btnPrev.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('[MBKM Wizard] Prev clicked, step:', currentStep);
            
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
            
            return false;
        });
        
        // Form submission
        form.on('submit', function(e) {
            console.log('[MBKM Wizard] Form submit triggered');
            
            if (!$('#agreement').is(':checked')) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Anda harus menyetujui pernyataan di atas sebelum submit!',
                        confirmButtonColor: '#22004C'
                    });
                } else {
                    alert('Anda harus menyetujui pernyataan di atas sebelum submit!');
                }
                return false;
            }
            
            // Show loading
            if ($('#loadingSpinner').length) {
                $('#loadingSpinner').show();
            }
        });
        
        // Initialize to step 1
        console.log('[MBKM Wizard] Initializing to step 1');
        showStep(1);
        
        console.log('[MBKM Wizard] Initialization complete!');
    });
})();
</script>
@endpush
                    text: 'Anda harus menyetujui pernyataan di atas sebelum submit!',
                });
                return false;
            }
            
            $('#loadingSpinner').show();
        });
        
        console.log('Initializing to step 1');
        showStep(1);
    });
</script>
@endpush
