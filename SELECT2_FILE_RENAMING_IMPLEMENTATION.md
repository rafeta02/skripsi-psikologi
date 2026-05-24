# Select2 Integration & File Renaming Implementation

## Overview
Implemented Select2 for all dropdown selections and custom file renaming format for uploaded documents in Skripsi Reguler and MBKM registration forms.

## Changes Made

### 1. Select2 Integration

#### Features Added:
- **Bootstrap4 Theme** - Consistent with existing UI
- **Searchable Dropdowns** - Easy to find options in long lists
- **Placeholder Support** - Clear instructions for users
- **Allow Clear** - Option to deselect
- **Custom Styling** - Matches design system colors

#### Forms Updated:
1. **Skripsi Reguler** (`resources/views/frontend/skripsi/create.blade.php`)
   - Bidang Keilmuan select
   - Dosen TPS select
   - Preferensi Dosen Pembimbing select

2. **MBKM** (`resources/views/frontend/mbkm/create.blade.php`)
   - Research Group select
   - Preferensi Dosen Pembimbing select
   - Bidang Keilmuan select

#### JavaScript Implementation:

```javascript
// Initialize Select2 for all select elements
if (typeof $.fn.select2 !== 'undefined') {
    $('#theme_id').select2({
        placeholder: '-- Pilih Bidang Keilmuan --',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
    
    $('#tps_lecturer_id').select2({
        placeholder: '-- Pilih Dosen TPS --',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
    
    $('#preference_supervision_id').select2({
        placeholder: '-- Pilih Dosen Pembimbing --',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
}
```

#### CSS Customization:

```css
/* Select2 Bootstrap4 Theme Customization */
.select2-container--bootstrap4 .select2-selection {
    border-radius: 8px !important;
    border: 1px solid #ced4da !important;
    min-height: 38px !important;
}

.select2-container--bootstrap4.select2-container--focus .select2-selection {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 0.2rem rgba(34, 0, 76, 0.25) !important;
}

.select2-container--bootstrap4 .select2-results__option--highlighted {
    background-color: var(--primary-color) !important;
}
```

### 2. File Renaming System

#### Naming Convention:

```
{TYPE}_{DOCTYPE}_{NIM}_{NAMA}_{EXTRA}_{TIMESTAMP}.{EXT}
```

Where:
- `TYPE` = Application type (SKRIPSI, MBKM)
- `DOCTYPE` = Document type (KHS, KRS, SPP, PROPOSAL, RECOGNITION, etc.)
- `NIM` = Student ID number
- `NAMA` = Student name (uppercase, slugified)
- `EXTRA` = Extra info (e.g., SEMESTER-1, SEMESTER-2)
- `TIMESTAMP` = YYYYMMDD_HHMMSS
- `EXT` = File extension

#### Examples:

**Skripsi Reguler:**
```
SKRIPSI_KHS_2023010001_BUDI-SANTOSO_SEMESTER-1_20260301_143022.pdf
SKRIPSI_KHS_2023010001_BUDI-SANTOSO_SEMESTER-2_20260301_143022.pdf
SKRIPSI_KHS_2023010001_BUDI-SANTOSO_SEMESTER-3_20260301_143022.pdf
SKRIPSI_KRS_2023010001_BUDI-SANTOSO_20260301_143022.pdf
```

**MBKM:**
```
MBKM_KHS_2023010001_BUDI-SANTOSO_SEMESTER-1_20260301_143022.pdf
MBKM_KRS_2023010001_BUDI-SANTOSO_20260301_143022.pdf
MBKM_SPP_2023010001_BUDI-SANTOSO_20260301_143022.pdf
MBKM_PROPOSAL_2023010001_BUDI-SANTOSO_20260301_143022.pdf
MBKM_RECOGNITION_2023010001_BUDI-SANTOSO_20260301_143022.pdf
```

#### Implementation in Controller:

```php
// Get mahasiswa data for filename
$mahasiswa = $application->mahasiswa;
$nim = $mahasiswa->nim ?? 'unknown';
$name = \Str::slug($mahasiswa->nama ?? 'mahasiswa');
$timestamp = now()->format('Ymd_His');

// Handle KHS files upload with custom naming
if ($request->hasFile('khs_all')) {
    $index = 1;
    foreach ($request->file('khs_all') as $file) {
        // Format: SKRIPSI_KHS_NIM_NAMA_SEMESTER-X_YYYYMMDD_HHMMSS.pdf
        $customFileName = sprintf(
            'SKRIPSI_KHS_%s_%s_SEMESTER-%d_%s.pdf',
            $nim,
            strtoupper($name),
            $index,
            $timestamp
        );
        
        $registration->addMedia($file)
            ->usingFileName($customFileName)
            ->toMediaCollection('khs_all');
        
        $index++;
    }
}

// Handle KRS file upload with custom naming
if ($request->hasFile('krs_latest')) {
    // Format: SKRIPSI_KRS_NIM_NAMA_YYYYMMDD_HHMMSS.pdf
    $customFileName = sprintf(
        'SKRIPSI_KRS_%s_%s_%s.pdf',
        $nim,
        strtoupper($name),
        $timestamp
    );
    
    $registration->addMedia($request->file('krs_latest'))
        ->usingFileName($customFileName)
        ->toMediaCollection('krs_latest');
}
```

### 3. File Input Enhancement

Added JavaScript to display selected filename(s):

```javascript
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
```

## Benefits

### Select2 Benefits:
1. ✅ **Better UX** - Searchable, easier to find options
2. ✅ **Consistent Design** - Bootstrap4 theme matches existing UI
3. ✅ **Accessibility** - Better keyboard navigation
4. ✅ **Mobile Friendly** - Touch-optimized
5. ✅ **Professional Look** - Modern dropdown design

### File Renaming Benefits:
1. ✅ **Organized** - Easy to identify files
2. ✅ **Traceable** - Contains student info and timestamp
3. ✅ **No Conflicts** - Timestamp ensures uniqueness
4. ✅ **Sortable** - Timestamp format allows chronological sorting
5. ✅ **Professional** - Standardized naming convention
6. ✅ **Searchable** - Can search by NIM, name, or date

## Files Modified

### Views:
1. ✅ `resources/views/frontend/skripsi/create.blade.php`
   - Added Select2 initialization
   - Added Select2 CSS customization
   - Added file input enhancement

2. ⏳ `resources/views/frontend/mbkm/create.blade.php`
   - Will add Select2 initialization
   - Will add Select2 CSS customization
   - Will add file input enhancement

### Controllers:
1. ✅ `app/Http/Controllers/Frontend/SkripsiRegistrationController.php`
   - Updated `store()` method with custom file naming
   - Added timestamp to `submitted_at` field

2. ⏳ `app/Http/Controllers/Frontend/MbkmRegistrationController.php`
   - Will update `store()` method with custom file naming
   - Will add timestamp to `submitted_at` field

## File Naming Matrix

| Application Type | Document Type | Naming Pattern |
|-----------------|---------------|----------------|
| Skripsi Reguler | KHS (multiple) | `SKRIPSI_KHS_{NIM}_{NAMA}_SEMESTER-{N}_{TIMESTAMP}.pdf` |
| Skripsi Reguler | KRS (single) | `SKRIPSI_KRS_{NIM}_{NAMA}_{TIMESTAMP}.pdf` |
| MBKM | KHS (multiple) | `MBKM_KHS_{NIM}_{NAMA}_SEMESTER-{N}_{TIMESTAMP}.pdf` |
| MBKM | KRS (single) | `MBKM_KRS_{NIM}_{NAMA}_{TIMESTAMP}.pdf` |
| MBKM | SPP (single) | `MBKM_SPP_{NIM}_{NAMA}_{TIMESTAMP}.pdf` |
| MBKM | Proposal MBKM | `MBKM_PROPOSAL_{NIM}_{NAMA}_{TIMESTAMP}.pdf` |
| MBKM | Recognition Form | `MBKM_RECOGNITION_{NIM}_{NAMA}_{TIMESTAMP}.pdf` |

## Testing Checklist

### Select2:
- [ ] Navigate to `/frontend/skripsi/1/create`
- [ ] Click on "Bidang Keilmuan" dropdown → Should open searchable select2
- [ ] Type to search → Should filter options
- [ ] Select an option → Should display selected value
- [ ] Click X button → Should clear selection
- [ ] Check all 3 dropdowns in Skripsi form
- [ ] Repeat for MBKM form at `/frontend/mbkm/1/create`

### File Renaming:
- [ ] Fill Skripsi form completely
- [ ] Upload KHS files (multiple)
- [ ] Upload KRS file
- [ ] Submit form
- [ ] Check database `media` table for renamed files
- [ ] Verify filename format matches pattern
- [ ] Download file to verify it's not corrupted
- [ ] Repeat for MBKM form

### File Input Enhancement:
- [ ] Click "Choose File" for KHS
- [ ] Select file → Label should show filename
- [ ] Select multiple files → Label should show "{N} file(s) dipilih"

## Dependencies

### Already Available in Layout:
- ✅ jQuery 3.3.1
- ✅ Select2 4.0.5 (from `select2.full.min.js`)
- ✅ Spatie Media Library (for file management)

### CSS:
Select2 Bootstrap4 theme CSS should be included in the layout. If not, add:

```html
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
```

## Date
2026-03-01

## Status
- ✅ Skripsi Reguler: COMPLETE
- ⏳ MBKM: IN PROGRESS
