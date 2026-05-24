# Skripsi Seminar - All Documents Required Update

## Change Request
User requested: "buat semua file required"

## Changes Made

### 1. Updated StoreSkripsiSeminarRequest.php

Changed validation rules to make ALL documents required:

```php
// BEFORE
'approval_document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
'plagiarism_document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

// AFTER
'approval_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
'plagiarism_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
```

Added required validation messages:
```php
'approval_document.required' => 'Dokumen persetujuan pembimbing wajib diupload.',
'plagiarism_document.required' => 'Dokumen hasil cek plagiarisme wajib diupload.',
```

### 2. Updated create.blade.php View

Changed form inputs to reflect required status:

**Approval Document:**
```blade
<!-- BEFORE -->
<label class="form-label-modern">Dokumen Persetujuan Pembimbing (PDF)</label>
<input type="file" name="approval_document" ... >
<small class="form-text text-muted">Upload surat persetujuan dari pembimbing (opsional)</small>

<!-- AFTER -->
<label class="form-label-modern required">Dokumen Persetujuan Pembimbing (PDF)</label>
<input type="file" name="approval_document" ... required>
<small class="form-text text-muted">Upload surat persetujuan dari pembimbing (Max: 10MB)</small>
```

**Plagiarism Document:**
```blade
<!-- BEFORE -->
<label class="form-label-modern">Hasil Plagiarism Check (PDF)</label>
<input type="file" name="plagiarism_document" ... >
<small class="form-text text-muted">Upload hasil pengecekan plagiarisme (opsional)</small>

<!-- AFTER -->
<label class="form-label-modern required">Hasil Plagiarism Check (PDF)</label>
<input type="file" name="plagiarism_document" ... required>
<small class="form-text text-muted">Upload hasil pengecekan plagiarisme (Max: 10MB)</small>
```

## Summary of Changes

| Field | Before | After |
|-------|--------|-------|
| proposal_document | Required ✅ | Required ✅ |
| approval_document | Optional ❌ | **Required ✅** |
| plagiarism_document | Optional ❌ | **Required ✅** |

## Files Modified

1. **app/Http/Requests/StoreSkripsiSeminarRequest.php**
   - Changed `approval_document` from `nullable` to `required`
   - Changed `plagiarism_document` from `nullable` to `required`
   - Added required validation messages

2. **resources/views/frontend/skripsiSeminars/create.blade.php**
   - Added `required` class to labels
   - Added `required` attribute to file inputs
   - Removed "(opsional)" text from help text
   - Updated help text to show max size instead

## Impact

- Students must now upload ALL 3 documents when registering for seminar:
  1. Proposal Document (PDF) - Required
  2. Approval Document (PDF) - Required (NEW)
  3. Plagiarism Check Document (PDF) - Required (NEW)

- Form validation will reject submissions missing any of the 3 documents
- Clear visual indicators (red asterisk) show required fields
- Browser-level validation (`required` attribute) provides instant feedback

## Note

The UpdateSkripsiSeminarRequest still has all documents as `nullable` because:
- When editing, files might not be replaced
- Only new files need validation
- Existing files are preserved if not replaced

This is intentional and correct behavior for update forms.
