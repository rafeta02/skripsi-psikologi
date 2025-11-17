# Skripsi Defense - Auto Application ID & Custom File Naming

## Overview
Sistem Sidang Skripsi (Skripsi Defense) adalah tahap akhir yang dilakukan mahasiswa untuk mempertahankan skripsinya. Form telah diupdate untuk:
1. **Auto-assign application_id** dari aplikasi aktif mahasiswa
2. **Custom file naming** menggunakan `FileNamingTrait` untuk semua dokumen upload

## Dokumen yang Diupload

Skripsi Defense memiliki **17 jenis dokumen** yang perlu diupload:

### Single File Documents (9):
1. `defence_document` - Dokumen sidang (max 25MB)
2. `plagiarism_report` - Laporan plagiarisme (max 10MB)
3. `mbkm_recommendation_letter` - Surat rekomendasi MBKM (max 10MB)
4. `publication_statement` - Pernyataan publikasi (max 10MB)
5. `spp_receipt` - Bukti pembayaran SPP (max 10MB)
6. `krs_latest` - KRS terbaru (max 10MB)
7. `eap_certificate` - Sertifikat EAP (max 10MB)
8. `transcript` - Transkrip nilai (max 10MB)
9. `siakad_supervisor_screenshot` - Screenshot pembimbing SIAKAD (max 10MB)

### Multiple Files Documents (8):
1. `ethics_statement` - Pernyataan etika penelitian
2. `research_instruments` - Instrumen penelitian
3. `data_collection_letter` - Surat izin pengumpulan data
4. `research_module` - Modul penelitian
5. `defense_approval_page` - Halaman persetujuan sidang
6. `mbkm_report` - Laporan MBKM
7. `research_poster` - Poster penelitian
8. `supervision_logbook` - Logbook bimbingan

## Changes Implemented

### 1. Model Enhancement
**File**: `app/Models/SkripsiDefense.php`

#### Added Trait:
```php
use App\Traits\FileNamingTrait;

class SkripsiDefense extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;
```

**Purpose**: Enable custom file naming for all uploaded documents.

### 2. Controller Updates
**File**: `app/Http/Controllers/Frontend/SkripsiDefenseController.php`

#### A. Updated `create()` Method:
```php
public function create()
{
    abort_if(Gate::denies('skripsi_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    // Get current mahasiswa's active application
    $user = auth()->user();
    $mahasiswa = $user->mahasiswa;
    
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan');
    }

    $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id)
        ->whereIn('status', ['submitted', 'approved', 'scheduled'])
        ->orderBy('created_at', 'desc')
        ->first();

    if (!$activeApplication) {
        return redirect()->back()->with('error', 'Tidak ada aplikasi aktif. Silakan buat aplikasi terlebih dahulu.');
    }

    return view('frontend.skripsiDefenses.create', compact('activeApplication'));
}
```

**Changes:**
- Removed `$applications` dropdown
- Fetch active application for current mahasiswa
- Pass `$activeApplication` to view
- Redirect with error if no active application

#### B. Updated `store()` Method:
Applied custom file naming to all 17 document types:

**Single File Example:**
```php
if ($request->input('defence_document', false)) {
    $filePath = storage_path('tmp/uploads/' . basename($request->input('defence_document')));
    $skripsiDefense->addMedia($filePath)
        ->usingFileName($skripsiDefense->generateCustomFileName($filePath, 'defence_document'))
        ->toMediaCollection('defence_document');
}
```

**Multiple Files Example:**
```php
foreach ($request->input('ethics_statement', []) as $file) {
    $filePath = storage_path('tmp/uploads/' . basename($file));
    $skripsiDefense->addMedia($filePath)
        ->usingFileName($skripsiDefense->generateCustomFileName($filePath, 'ethics_statement'))
        ->toMediaCollection('ethics_statement');
}
```

**Pattern Applied to:**
- ✅ defence_document
- ✅ plagiarism_report
- ✅ ethics_statement (array)
- ✅ research_instruments (array)
- ✅ data_collection_letter (array)
- ✅ research_module (array)
- ✅ mbkm_recommendation_letter
- ✅ publication_statement
- ✅ defense_approval_page (array)
- ✅ spp_receipt
- ✅ krs_latest
- ✅ eap_certificate
- ✅ transcript
- ✅ mbkm_report (array)
- ✅ research_poster (array)
- ✅ siakad_supervisor_screenshot
- ✅ supervision_logbook (array)

#### C. Updated `edit()` Method:
```php
public function edit(SkripsiDefense $skripsiDefense)
{
    abort_if(Gate::denies('skripsi_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $skripsiDefense->load('application', 'created_by');

    return view('frontend.skripsiDefenses.edit', compact('skripsiDefense'));
}
```

**Changes:**
- Removed `$applications` dropdown
- Application ID is read-only (from existing record)

#### D. Updated `update()` Method:
Applied custom file naming to all 17 document types (same pattern as store).

### 3. View Updates

#### A. Create Form
**File**: `resources/views/frontend/skripsiDefenses/create.blade.php`

**Before:**
```blade
<select class="form-control select2" name="application_id" id="application_id">
    @foreach($applications as $id => $entry)
        <option value="{{ $id }}">{{ $entry }}</option>
    @endforeach
</select>
```

**After:**
```blade
@if($activeApplication)
    <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">
    <div class="alert alert-success mb-4">
        <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>Aplikasi Skripsi Anda</h5>
        <p class="mb-1"><strong>Type:</strong> <span class="badge badge-primary">{{ ucfirst($activeApplication->type) }}</span></p>
        <p class="mb-1"><strong>Stage:</strong> <span class="badge badge-info">{{ ucfirst($activeApplication->stage) }}</span></p>
        <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($activeApplication->status) }}</span></p>
    </div>
@else
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        Anda belum memiliki aplikasi skripsi yang aktif. Silakan buat aplikasi terlebih dahulu.
    </div>
@endif
```

**Submit Button:**
```blade
<button class="btn btn-danger" type="submit" {{ !$activeApplication ? 'disabled' : '' }}>
    {{ trans('global.save') }}
</button>
```

#### B. Edit Form
**File**: `resources/views/frontend/skripsiDefenses/edit.blade.php`

**Before:**
```blade
<select class="form-control select2" name="application_id" id="application_id">
    @foreach($applications as $id => $entry)
        <option value="{{ $id }}" {{ $skripsiDefense->application->id == $id ? 'selected' : '' }}>{{ $entry }}</option>
    @endforeach
</select>
```

**After:**
```blade
@if($skripsiDefense->application)
    <input type="hidden" name="application_id" value="{{ $skripsiDefense->application->id }}">
    <div class="alert alert-info mb-4">
        <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>Aplikasi Skripsi</h5>
        <p class="mb-1"><strong>Type:</strong> <span class="badge badge-primary">{{ ucfirst($skripsiDefense->application->type) }}</span></p>
        <p class="mb-1"><strong>Stage:</strong> <span class="badge badge-info">{{ ucfirst($skripsiDefense->application->stage) }}</span></p>
        <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($skripsiDefense->application->status) }}</span></p>
    </div>
@endif
```

## File Naming Convention

### Format
All uploaded files follow the pattern from `FileNamingTrait`:
```
{NIM}_{field_name}_{timestamp}.{extension}
```

### Examples
**For single files:**
- `220101001_defence_document_20250115_143022.pdf`
- `220101001_plagiarism_report_20250115_143045.pdf`
- `220101001_transcript_20250115_143100.pdf`

**For multiple files:**
- `220101001_ethics_statement_20250115_143120.pdf`
- `220101001_research_instruments_20250115_143135.pdf`
- `220101001_supervision_logbook_20250115_143150.pdf`

### Benefits
1. **Unique identification**: Each file is uniquely identified by NIM and timestamp
2. **Easy tracking**: Files can be traced back to the student
3. **No conflicts**: Timestamp ensures no file name conflicts
4. **Organized storage**: Consistent naming makes file management easier
5. **Audit trail**: File names provide information about when they were uploaded

## User Flow

### Creating Skripsi Defense

```
1. Mahasiswa navigates to "Skripsi Defenses" → "Create"
   ↓
2. System checks for active application
   ↓
3a. If active application exists:
    - Display success alert with application details
    - Show all document upload fields
    - Enable submit button
   ↓
3b. If NO active application:
    - Display warning alert
    - Disable submit button
    - Prompt to create application first
   ↓
4. Mahasiswa uploads 17 required documents
   ↓
5. Each file is renamed using custom naming convention
   ↓
6. Submit form → Skripsi Defense created with auto-assigned application_id
```

### Editing Skripsi Defense

```
1. Mahasiswa navigates to existing defense → "Edit"
   ↓
2. System displays application details in info alert
   ↓
3. Application ID is read-only (hidden input)
   ↓
4. Mahasiswa can update documents
   ↓
5. Updated files are renamed using custom naming convention
   ↓
6. Submit → Changes saved
```

## Active Application Logic

### Criteria for Active Application:
```php
Application::where('mahasiswa_id', $mahasiswa->id)
    ->whereIn('status', ['submitted', 'approved', 'scheduled'])
    ->orderBy('created_at', 'desc')
    ->first();
```

**Status Accepted:**
- `submitted` - Application has been submitted
- `approved` - Application has been approved
- `scheduled` - Defense has been scheduled

**Ordering:**
- Most recent application is selected (`orderBy('created_at', 'desc')`)

## Security & Validation

### Controller Level:
1. **Gate Check**: `abort_if(Gate::denies('skripsi_defense_create'))`
2. **Mahasiswa Verification**: Check if user has mahasiswa profile
3. **Active Application Check**: Ensure student has valid application
4. **File Path Security**: Use `basename()` to prevent directory traversal

### View Level:
1. **Conditional Display**: Only show form if active application exists
2. **Disabled Submit**: Button disabled when no active application
3. **Visual Feedback**: Clear alerts inform user of application status

### File Upload:
1. **Size Limits**: 
   - Defence document: 25MB max
   - Other documents: 10MB max
2. **Custom Naming**: Prevents file overwrites
3. **Media Collections**: Files organized by type
4. **Validation**: File type and size validated server-side

## Benefits

### For Mahasiswa:
1. **Simplified Process**
   - No need to select application (auto-assigned)
   - Clear visibility of current application
   - Cannot submit without active application

2. **Better UX**
   - Alert boxes provide clear information
   - Visual badges for application status
   - Disabled state prevents errors

3. **Organized Documents**
   - All defense documents in one place
   - Clear upload fields for each requirement
   - Files automatically named consistently

### For Admin:
1. **File Management**
   - Consistent file naming across all uploads
   - Easy to identify student files
   - Timestamp for audit trail

2. **Data Integrity**
   - Application ID always valid (from active application)
   - No orphaned defense records
   - Strong relationship between defense and application

3. **Tracking**
   - Know exactly which application defense belongs to
   - Easy to trace files back to students
   - Clear upload history

## Database Relations

```
SkripsiDefense
  ↓
  belongsTo: Application
    ↓
    belongsTo: Mahasiswa
```

**Media Collections:**
- Each of the 17 document types has its own media collection
- Files stored using Spatie Media Library
- Custom file naming applied to all collections

## Testing Checklist

### Create Defense:
- [ ] Can access create form
- [ ] Active application info displayed correctly
- [ ] No active application → warning shown
- [ ] Submit button disabled when no active application
- [ ] All 17 document fields present
- [ ] Files upload successfully
- [ ] Files renamed with custom convention
- [ ] Defense created with correct application_id
- [ ] Redirect to index after creation

### Edit Defense:
- [ ] Can access edit form
- [ ] Application info displayed (read-only)
- [ ] Can update documents
- [ ] Updated files renamed with custom convention
- [ ] Changes saved correctly
- [ ] Application ID remains unchanged

### File Naming:
- [ ] Single files named correctly
- [ ] Multiple files named correctly
- [ ] NIM included in filename
- [ ] Timestamp included in filename
- [ ] Original extension preserved
- [ ] No file name conflicts

### Edge Cases:
- [ ] User without mahasiswa profile → error message
- [ ] No active application → cannot create defense
- [ ] Multiple active applications → most recent selected
- [ ] File size exceeds limit → validation error
- [ ] Invalid file type → validation error

## Files Modified Summary

### Models:
1. ✅ `app/Models/SkripsiDefense.php`
   - Added `FileNamingTrait`

### Controllers:
2. ✅ `app/Http/Controllers/Frontend/SkripsiDefenseController.php`
   - Updated `create()`: Get active application
   - Updated `store()`: Custom file naming for all 17 documents
   - Updated `edit()`: Removed applications dropdown
   - Updated `update()`: Custom file naming for all 17 documents

### Views:
3. ✅ `resources/views/frontend/skripsiDefenses/create.blade.php`
   - Hidden application_id input
   - Success alert with application details
   - Warning alert if no active application
   - Disabled submit button when no active application

4. ✅ `resources/views/frontend/skripsiDefenses/edit.blade.php`
   - Hidden application_id input
   - Info alert with application details (read-only)

## Consistency with Other Forms

This implementation follows the same pattern as:
- ✅ ApplicationReport
- ✅ ApplicationResultSeminar
- ✅ ApplicationResultDefense
- ✅ ApplicationSchedule

**Common Features:**
- Auto-assigned application_id from active application
- Hidden input with alert box display
- Custom file naming using FileNamingTrait
- Disabled submit when no active application
- Read-only application info on edit forms

## Summary

Skripsi Defense form telah diupdate untuk:
- ✅ **Auto-assign application_id** dari aplikasi aktif mahasiswa
- ✅ **Custom file naming** untuk semua 17 jenis dokumen
- ✅ **Improved UX** dengan alert boxes informatif
- ✅ **Validation** untuk memastikan mahasiswa punya aplikasi aktif
- ✅ **Consistency** dengan form-form lain dalam sistem
- ✅ **File organization** dengan naming convention yang konsisten

Mahasiswa sekarang tidak perlu memilih application secara manual, dan semua file upload akan ter-rename secara otomatis mengikuti konvensi yang telah ditetapkan! 🎉

