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
    
    .select2-container--bootstrap4 .select2-selection--multiple {
        min-height: 38px !important;
        padding: 4px 8px !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        margin-right: 4px !important;
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
    
    .form-control.is-invalid,
    .form-control-file.is-invalid {
        border-color: #dc3545;
    }
    
    .select2-container.is-invalid .select2-selection {
        border-color: #dc3545 !important;
    }
    
    .custom-control-input.is-invalid ~ .custom-control-label {
        color: #dc3545;
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
                <div class="wizard-step-title">Data Kelompok</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="wizard-step-number">3</div>
                <div class="wizard-step-title">Anggota</div>
            </div>
            <div class="wizard-step" data-step="4">
                <div class="wizard-step-number">4</div>
                <div class="wizard-step-title">Syarat Individu</div>
            </div>
            <div class="wizard-step" data-step="5">
                <div class="wizard-step-number">5</div>
                <div class="wizard-step-title">Dokumen</div>
            </div>
            <div class="wizard-step" data-step="6">
                <div class="wizard-step-number">6</div>
                <div class="wizard-step-title">Konfirmasi</div>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('frontend.mbkm.store', $application->id) }}" method="POST" enctype="multipart/form-data" id="mbkmForm" novalidate>
            @csrf
            
            <!-- Step 1: Research Group & Pembimbing -->
            <div class="form-section active" data-section="1">
                <h4 class="mb-4">Research Group & Dosen Pembimbing</h4>
                
                <div class="form-group">
                    <label for="research_group_id">Research Group <span class="text-danger">*</span></label>
                    <select name="research_group_id" id="research_group_id" class="form-control" required>
                        <option value="">-- Pilih Research Group --</option>
                        @foreach($researchGroups as $id => $name)
                            <option value="{{ $id }}" {{ old('research_group_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="preference_supervision_id">Pilih Dosen Pembimbing <span class="text-danger">*</span></label>
                    <select name="preference_supervision_id" id="preference_supervision_id" class="form-control" required disabled>
                        <option value="">-- Pilih Research Group terlebih dahulu --</option>
                    </select>
                    <small class="form-text text-muted">Dosen difilter sesuai research group yang dipilih</small>
                </div>
            </div>
            
            <!-- Step 2: Data Topik Kelompok -->
            <div class="form-section" data-section="2">
                <h4 class="mb-4">Data Kelompok MBKM</h4>
                <div class="alert alert-secondary">Field di langkah ini milik <strong>kelompok</strong> (diisi ketua).</div>
                
                <div class="form-group">
                    <label for="theme_ids">Tema Riset <span class="text-danger">*</span></label>
                    <select name="theme_ids[]" id="theme_ids" class="form-control" multiple required>
                        @foreach($keilmuans as $id => $name)
                            <option value="{{ $id }}" {{ collect(old('theme_ids'))->contains($id) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Anda dapat memilih lebih dari satu tema riset</small>
                </div>
                
                <div class="form-group">
                    <label for="title_mbkm">Judul Kegiatan MBKM <span class="text-danger">*</span></label>
                    <textarea name="title_mbkm" id="title_mbkm" class="form-control" rows="2" required>{{ old('title_mbkm') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="note">Catatan Tambahan</label>
                    <textarea name="note" id="note" class="form-control" rows="3"
                        placeholder="Masukkan catatan atau keterangan tambahan jika ada">{{ old('note') }}</textarea>
                </div>
            </div>

            <!-- Step 3: Anggota Kelompok -->
            <div class="form-section" data-section="3">
                <h4 class="mb-4">Anggota Kelompok MBKM</h4>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Satu form mewakili satu kelompok. Anda (pengisi form) otomatis menjadi <strong>ketua</strong>.
                    Tambahkan anggota lain lewat NIM. Semua anggota akan tercatat sampai tahap yang sama hingga sebelum sidang.
                </div>

                <div class="form-group">
                    <label>Cari Anggota (NIM)</label>
                    <div class="input-group">
                        <input type="text" id="member_nim_search" class="form-control" placeholder="Masukkan NIM anggota">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" id="btnSearchMember">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted" id="memberSearchHint">Opsional — boleh kosong jika mengerjakan sendiri.</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="groupMembersTable">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th width="140">Peran</th>
                                <th width="80"></th>
                            </tr>
                        </thead>
                        <tbody id="groupMembersBody">
                            <tr class="text-muted" id="groupMembersEmpty">
                                <td colspan="4" class="text-center">Belum ada anggota tambahan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="groupMembersInputs"></div>
            </div>
            
            <!-- Step 4: Syarat Individu Ketua -->
            <div class="form-section" data-section="4">
                <h4 class="mb-4">Syarat Individu (Ketua)</h4>
                <div class="alert alert-info">Judul skripsi dan nilai ini milik <strong>Anda sebagai individu</strong>. Anggota mengisi di halaman mereka sendiri.</div>

                <div class="form-group">
                    <label for="title">Judul Skripsi <span class="text-danger">*</span></label>
                    <textarea name="title" id="title" class="form-control" rows="3" required
                        placeholder="Tuliskan judul skripsi dalam Bahasa Indonesia">{{ old('title') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="title_en">Judul Skripsi (English)</label>
                    <textarea name="title_en" id="title_en" class="form-control" rows="3"
                        placeholder="Thesis title in English (optional)">{{ old('title_en') }}</textarea>
                </div>
                
                <h5 class="mt-4 mb-3">Nilai</h5>
                
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
            
            <!-- Step 5: Upload Dokumen -->
            <div class="form-section" data-section="5">
                <h4 class="mb-4">Dokumen</h4>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Proposal MBKM = dokumen <strong>kelompok</strong>. KHS/KRS/SPP = dokumen <strong>individu</strong> Anda.
                </div>

                <h5 class="mb-3">Dokumen Kelompok</h5>
                <div class="form-group">
                    <label for="proposal_mbkm">Proposal MBKM <span class="text-danger">*</span></label>
                    <input type="file" name="proposal_mbkm" id="proposal_mbkm" class="form-control-file" required accept=".pdf">
                    <small class="form-text text-muted">Max 10MB</small>
                </div>

                <h5 class="mt-4 mb-3">Dokumen Individu (Ketua)</h5>
                
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
                    <label for="recognition_form">Form Rekognisi (Opsional)</label>
                    <input type="file" name="recognition_form" id="recognition_form" class="form-control-file" accept=".pdf">
                    <small class="form-text text-muted">Max 5MB</small>
                </div>
            </div>
            
            <!-- Step 6: Konfirmasi -->
            <div class="form-section" data-section="6">
                <h4 class="mb-4">Konfirmasi Data</h4>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Menyimpan draft kelompok. Setelah semua anggota melengkapi syarat individu, Anda dapat <strong>Submit Pengajuan Kelompok</strong> ke admin.
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
                    <i class="fas fa-save mr-2"></i> Simpan Draft Kelompok
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
    const totalSteps = 6;
    
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
    
    function getFieldLabel(field) {
        const group = field.closest('.form-group');
        const label = group ? group.querySelector('label') : null;
        if (label) {
            return label.textContent.replace(/\*/g, '').trim();
        }
        return field.name || 'Field';
    }
    
    function isFieldValid(field) {
        if (field.disabled) {
            return true;
        }
        
        if (field.type === 'checkbox') {
            return field.checked;
        }
        
        if (field.type === 'file') {
            return field.files && field.files.length > 0;
        }
        
        if (field.tagName === 'SELECT') {
            if (field.multiple) {
                return field.selectedOptions && field.selectedOptions.length > 0;
            }
            return field.value !== '' && field.value !== null;
        }
        
        return String(field.value).trim() !== '';
    }
    
    function setFieldValidState(field, isValid) {
        field.classList.toggle('is-invalid', !isValid);
        
        if (field.tagName === 'SELECT' && $(field).hasClass('select2-hidden-accessible')) {
            $(field).next('.select2-container').toggleClass('is-invalid', !isValid);
        }
    }
    
    function validateStep(step) {
        const section = document.querySelector(`.form-section[data-section="${step}"]`);
        if (!section) {
            return { valid: true, missing: [], firstInvalid: null, step: step };
        }
        
        const fields = section.querySelectorAll('input[required], select[required], textarea[required]');
        const missing = [];
        let firstInvalid = null;
        
        fields.forEach(function(field) {
            const valid = isFieldValid(field);
            setFieldValidState(field, valid);
            
            if (!valid) {
                missing.push(getFieldLabel(field));
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });
        
        return {
            valid: missing.length === 0,
            missing: missing,
            firstInvalid: firstInvalid,
            step: step
        };
    }
    
    function showValidationWarning(missing) {
        const list = missing.map(function(label) {
            return '• ' + label;
        }).join('<br>');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                html: 'Mohon lengkapi field berikut:<br><br>' + list,
                confirmButtonColor: '#22004C'
            });
        } else {
            alert('Mohon lengkapi field berikut:\n\n' + missing.join('\n'));
        }
    }
    
    function updateSummary() {
        const researchGroup = $('#research_group_id option:selected').text() || '-';
        const supervisor = $('#preference_supervision_id option:selected').text() || '-';
        const themes = ($('#theme_ids').select2('data') || [])
            .map(function(item) { return item.text; })
            .filter(Boolean)
            .join(', ') || '-';
        const titleMbkm = $('#title_mbkm').val() || '-';
        const title = $('#title').val() || '-';
        const totalSks = $('#total_sks_taken').val() || '-';
        const groupMemberSummary = (typeof getGroupMemberSummary === 'function')
            ? getGroupMemberSummary()
            : 'Hanya ketua (Anda)';
        
        let summary = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Research Group:</strong><br>${researchGroup}</p>
                    <p><strong>Dosen Pembimbing:</strong><br>${supervisor}</p>
                    <p><strong>Tema Riset:</strong><br>${themes}</p>
                    <p><strong>Anggota Kelompok:</strong><br>${groupMemberSummary}</p>
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

    const dosensByGroup = @json($dosensByGroup ?? []);
    const searchMahasiswaUrl = @json(route('frontend.mbkm.search-mahasiswa'));
    let groupMembers = [];

    function renderGroupMembers() {
        const $body = $('#groupMembersBody');
        const $inputs = $('#groupMembersInputs');
        $body.empty();
        $inputs.empty();

        if (groupMembers.length === 0) {
            $body.append('<tr class="text-muted" id="groupMembersEmpty"><td colspan="4" class="text-center">Belum ada anggota tambahan</td></tr>');
            return;
        }

        groupMembers.forEach(function(member, index) {
            $body.append(`
                <tr data-id="${member.id}">
                    <td>${member.nim}</td>
                    <td>${member.nama}</td>
                    <td><span class="badge badge-secondary">Anggota</span></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-member" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            $inputs.append(`
                <input type="hidden" name="group_members[${index}][mahasiswa_id]" value="${member.id}">
                <input type="hidden" name="group_members[${index}][role]" value="anggota">
            `);
        });
    }

    function addGroupMember(mahasiswa) {
        if (groupMembers.some(function(m) { return String(m.id) === String(mahasiswa.id); })) {
            alert('Mahasiswa sudah ditambahkan.');
            return;
        }
        groupMembers.push({
            id: mahasiswa.id,
            nim: mahasiswa.nim,
            nama: mahasiswa.nama,
            role: 'anggota',
        });
        renderGroupMembers();
    }

    function getGroupMemberSummary() {
        if (groupMembers.length === 0) {
            return 'Hanya ketua (Anda)';
        }
        return groupMembers.map(function(m) {
            return m.nama + ' (' + m.nim + ') — ' + m.role;
        }).join('<br>');
    }

    function refillSupervisors(groupId, selectedId) {
        const $select = $('#preference_supervision_id');
        $select.empty();

        if (!groupId) {
            $select.append('<option value="">-- Pilih Research Group terlebih dahulu --</option>');
            $select.prop('disabled', true).val('').trigger('change');
            return;
        }

        const dosens = dosensByGroup[groupId] || dosensByGroup[String(groupId)] || [];
        $select.append('<option value="">-- Pilih Dosen Pembimbing --</option>');

        dosens.forEach(function(dosen) {
            const selected = String(selectedId) === String(dosen.id) ? ' selected' : '';
            $select.append('<option value="' + dosen.id + '"' + selected + '>' + dosen.nama + '</option>');
        });

        $select.prop('disabled', false);
        if (selectedId) {
            $select.val(String(selectedId));
        } else {
            $select.val('');
        }
        $select.trigger('change');
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
            
            $('#theme_ids').select2({
                placeholder: '-- Pilih Tema Riset --',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                dropdownAutoWidth: false,
                closeOnSelect: false
            });

            $('#research_group_id').on('change', function() {
                refillSupervisors($(this).val(), null);
            });

            @if(old('research_group_id'))
                refillSupervisors('{{ old('research_group_id') }}', '{{ old('preference_supervision_id') }}');
            @endif
            
            console.log('[MBKM Wizard] Select2 initialized');
        } else {
            console.warn('[MBKM Wizard] Select2 library not found');
        }

        $('#btnSearchMember').on('click', function() {
            const nim = ($('#member_nim_search').val() || '').trim();
            if (!nim) {
                alert('Masukkan NIM terlebih dahulu.');
                return;
            }

            $('#memberSearchHint').text('Mencari...');
            $.get(searchMahasiswaUrl, { nim: nim })
                .done(function(res) {
                    if (!res.found) {
                        $('#memberSearchHint').text(res.message || 'Tidak ditemukan');
                        return;
                    }
                    addGroupMember(res.mahasiswa);
                    $('#member_nim_search').val('');
                    $('#memberSearchHint').text('Anggota ditambahkan.');
                })
                .fail(function() {
                    $('#memberSearchHint').text('Gagal mencari mahasiswa.');
                });
        });

        $(document).on('click', '.btn-remove-member', function() {
            const index = parseInt($(this).data('index'), 10);
            groupMembers.splice(index, 1);
            renderGroupMembers();
        });

        $(document).on('change', '.member-role', function() {
            const index = parseInt($(this).data('index'), 10);
            groupMembers[index].role = $(this).val();
            $('.member-role-input[data-index="' + index + '"]').val($(this).val());
        });

        @if(old('group_members'))
            @foreach(old('group_members') as $oldMember)
                @if(!empty($oldMember['mahasiswa_id']))
                    @php $m = \App\Models\Mahasiswa::find($oldMember['mahasiswa_id']); @endphp
                    @if($m)
                        groupMembers.push({
                            id: {{ $m->id }},
                            nim: @json($m->nim),
                            nama: @json($m->nama),
                            role: @json($oldMember['role'] ?? 'anggota'),
                        });
                    @endif
                @endif
            @endforeach
            renderGroupMembers();
        @endif
        
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
        form.off('input change', 'input, select, textarea');
        
        // Clear invalid state when user fills a field
        form.on('input change', 'input, select, textarea', function() {
            setFieldValidState(this, isFieldValid(this));
        });
        
        // Next button
        btnNext.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('[MBKM Wizard] Next clicked, step:', currentStep);
            
            const result = validateStep(currentStep);
            if (!result.valid) {
                showValidationWarning(result.missing);
                if (result.firstInvalid) {
                    result.firstInvalid.focus();
                }
                return false;
            }
            
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
            
            let allMissing = [];
            let firstInvalidStep = null;
            
            for (let step = 1; step <= 5; step++) {
                const result = validateStep(step);
                if (!result.valid) {
                    allMissing = allMissing.concat(result.missing);
                    if (!firstInvalidStep) {
                        firstInvalidStep = step;
                    }
                }
            }
            
            if (allMissing.length > 0) {
                e.preventDefault();
                showValidationWarning(allMissing);
                if (firstInvalidStep) {
                    showStep(firstInvalidStep);
                }
                return false;
            }
            
            if (!$('#agreement').is(':checked')) {
                e.preventDefault();
                $('#agreement').addClass('is-invalid');
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
            
            $('#agreement').removeClass('is-invalid');
            
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
