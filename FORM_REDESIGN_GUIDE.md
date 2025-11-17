# Panduan Redesign Form - Modern Style

## Overview
Semua form telah didesain ulang menggunakan style modern dengan base color **#22004C** (ungu tua). File CSS terpisah telah dibuat di `public/css/modern-form.css` untuk memudahkan maintenance.

## File CSS
**Location:** `public/css/modern-form.css`

File ini berisi semua style yang diperlukan untuk:
- Form cards (create/edit)
- Wizard multi-step forms
- List/Index pages
- Detail/Show pages
- Status badges
- Buttons dan actions

## Cara Menggunakan

### 1. Tambahkan CSS di Layout
Tambahkan di `resources/views/layouts/frontend.blade.php`:

```html
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
```

### 2. Template untuk Form Create/Edit (Simple Form)

```blade
@extends('layouts.frontend')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <!-- Header -->
                <div class="form-header">
                    <h2>Judul Form</h2>
                    <p>Deskripsi singkat form</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('...') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST') <!-- atau PUT untuk edit -->
                    
                    <div class="form-body">
                        <!-- Info Box (Optional) -->
                        <div class="info-box">
                            <div class="info-box-title">Informasi Penting</div>
                            <div class="info-box-text">
                                Teks informasi...
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="form-group">
                            <label for="field_name">
                                Label <span class="required">*</span>
                            </label>
                            <input class="form-control" type="text" name="field_name" id="field_name" value="{{ old('field_name', '') }}" required>
                            @if($errors->has('field_name'))
                                <span class="text-danger small">{{ $errors->first('field_name') }}</span>
                            @endif
                            <span class="help-block">Help text</span>
                        </div>

                        <!-- Repeat for other fields -->
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <a href="{{ route('...index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 3. Template untuk Index/List Page

```blade
@extends('layouts.frontend')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1>Judul Halaman</h1>
                    <p>Deskripsi halaman</p>
                </div>
            </div>

            <!-- Info Banner (Optional) -->
            <div class="info-box">
                <div class="info-box-title">Informasi</div>
                <div class="info-box-text">Teks informasi...</div>
            </div>

            <!-- List Card -->
            <div class="list-card">
                <div class="card-header-custom">
                    <h2 class="card-title">Daftar Data</h2>
                    @can('permission_create')
                        <a class="btn-add" href="{{ route('...create') }}">
                            <i class="fas fa-plus"></i> Tambah Baru
                        </a>
                    @endcan
                </div>

                <div class="table-wrapper" style="padding: 2rem;">
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <!-- Table content -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 4. Template untuk Show/Detail Page

```blade
@extends('layouts.frontend')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <h2>Detail Data</h2>
                    <div class="subtitle">Informasi lengkap</div>
                </div>

                <!-- Body -->
                <div class="detail-body">
                    <!-- Section -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Dasar
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Field Label</div>
                            <div class="info-value">{{ $data->field ?? '-' }}</div>
                        </div>

                        <!-- Repeat for other fields -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a class="btn-back" href="{{ route('...index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('permission_edit')
                        <a class="btn-submit" href="{{ route('...edit', $data->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

## Daftar Form yang Perlu Diupdate

### ✅ Completed
1. **skripsiRegistrations** - Sudah menggunakan desain modern
2. **applicationReports/create.blade.php** - Sudah diupdate

### 📋 To Do
Gunakan template di atas untuk update file-file berikut:

#### 1. applicationReports
- [x] create.blade.php (Done)
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 2. applicationSchedules
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 3. applicationResultSeminars
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 4. applicationResultDefenses
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 5. mbkmRegistrations
- [ ] create.blade.php (Use wizard like skripsiRegistrations)
- [ ] edit.blade.php (Use wizard like skripsiRegistrations)
- [ ] index.blade.php
- [ ] show.blade.php

#### 6. mbkmSeminars
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 7. skripsiSeminars
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] index.blade.php
- [ ] show.blade.php

#### 8. skripsiDefenses
- [ ] create.blade.php (Complex - many fields, use wizard)
- [ ] edit.blade.php (Complex - many fields, use wizard)
- [ ] index.blade.php
- [ ] show.blade.php

## Tips untuk Implementasi

### 1. Dropzone
Untuk file upload, gunakan struktur ini:
```html
<div class="form-group">
    <label for="field">Label <span class="required">*</span></label>
    <div class="needsclick dropzone" id="field-dropzone">
        <div class="dz-message">
            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
            <p>Klik atau seret file ke sini</p>
            <small>Format yang diterima, maksimal 10 MB</small>
        </div>
    </div>
    @if($errors->has('field'))
        <span class="text-danger small">{{ $errors->first('field') }}</span>
    @endif
    <span class="help-block">Help text</span>
</div>
```

### 2. Select2
Tetap gunakan class `select2` pada select element:
```html
<select class="form-control select2" name="field" id="field">
    <option value="">-- Pilih --</option>
    @foreach($items as $id => $entry)
        <option value="{{ $id }}">{{ $entry }}</option>
    @endforeach
</select>
```

### 3. Status Badges
Gunakan class yang sesuai:
```html
<span class="status-badge status-{{ $status }}">
    {{ ucfirst($status) }}
</span>
```

Available status classes:
- `status-submitted`
- `status-approved`
- `status-rejected`
- `status-scheduled`
- `status-done`
- `status-reviewed`
- `status-assigned`
- `status-accepted`

### 4. Info Box Variants
```html
<!-- Default (blue) -->
<div class="info-box">...</div>

<!-- Warning (yellow) -->
<div class="info-box warning">...</div>

<!-- Success (green) -->
<div class="info-box success">...</div>
```

### 5. Wizard Forms
Untuk form kompleks seperti skripsiDefenses dan mbkmRegistrations, gunakan wizard multi-step seperti yang sudah ada di skripsiRegistrations.

## Color Scheme
- **Primary:** `#22004C` (Dark Purple)
- **Secondary:** `#4A0080` (Medium Purple)
- **Gradient:** `linear-gradient(135deg, #22004C 0%, #4A0080 100%)`
- **Success:** `#48bb78` (Green)
- **Warning:** `#ffc107` (Yellow)
- **Danger:** `#e53e3e` (Red)
- **Info:** `#4299e1` (Blue)

## Icons (Font Awesome)
Gunakan icon yang sesuai:
- Form: `fa-edit`, `fa-plus`, `fa-save`
- Navigation: `fa-arrow-left`, `fa-arrow-right`
- Info: `fa-info-circle`, `fa-exclamation-circle`
- Files: `fa-file-pdf`, `fa-cloud-upload-alt`
- Actions: `fa-check`, `fa-times`, `fa-trash`

## Responsive Considerations
CSS sudah include responsive styles untuk mobile. Pastikan:
- Container menggunakan `col-lg-10` atau `col-lg-12`
- Table menggunakan `table-responsive`
- Form actions menggunakan flexbox yang akan stack di mobile

## Testing Checklist
Setelah update setiap form, pastikan:
- [ ] Header gradient tampil dengan benar
- [ ] Form fields memiliki border dan focus state yang tepat
- [ ] Dropzone hover effect bekerja
- [ ] Buttons memiliki hover effect
- [ ] Status badges tampil dengan warna yang benar
- [ ] Responsive di mobile (test dengan browser dev tools)
- [ ] Validation errors tampil dengan baik
- [ ] Help text readable

## Next Steps
1. Link CSS file di layout
2. Update form satu per satu menggunakan template
3. Test setiap form setelah update
4. Commit changes per module untuk tracking yang lebih baik
