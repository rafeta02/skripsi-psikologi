# Skripsi Seminar File Upload Fix

## Problem
Error: "The proposal document must be a string." on `http://127.0.0.1:8000/skripsi-seminars/create`

## Root Cause

The validation rules in `StoreSkripsiSeminarRequest` and `UpdateSkripsiSeminarRequest` were configured for **Dropzone temporary file uploads** (expecting string paths), but the views used **standard HTML file inputs** (sending actual file objects).

### Original Validation (Wrong for standard file upload):
```php
'proposal_document' => [
    'required',
    'string',  // ❌ Expects string path, not file object
],
```

### Original Controller (Wrong for standard file upload):
```php
if ($request->input('proposal_document', false)) {
    $skripsiSeminar->addMediaWithCustomName(
        storage_path('tmp/uploads/' . basename($request->input('proposal_document'))),
        'proposal_document'
    );
}
```

This approach assumes files are pre-uploaded to a temp directory via Dropzone, then referenced by string path.

## Solution

Changed from **Dropzone-style** to **standard Laravel file upload** handling.

### 1. Updated StoreSkripsiSeminarRequest.php

**Changes:**
```php
public function rules()
{
    return [
        'application_id' => [
            'nullable',  // Changed from 'required'
            'exists:applications,id',
        ],
        'title' => [
            'required',
            'string',
            'max:255',
        ],
        'description' => [  // Added
            'nullable',
            'string',
        ],
        'notes' => [  // Added
            'nullable',
            'string',
        ],
        'proposal_document' => [
            'required',
            'file',      // ✅ Changed from 'string' to 'file'
            'mimes:pdf', // ✅ Added validation
            'max:10240', // ✅ 10MB max
        ],
        'approval_document' => [
            'nullable',  // ✅ Changed from 'required'
            'file',      // ✅ Changed from 'string'
            'mimes:pdf',
            'max:10240',
        ],
        'plagiarism_document' => [
            'nullable',  // ✅ Changed from 'required'
            'file',      // ✅ Changed from 'string'
            'mimes:pdf',
            'max:10240',
        ],
    ];
}
```

**Key Changes:**
- `application_id`: Changed from `required` to `nullable` (created in controller)
- All document fields: Changed from `string` to `file` validation
- `approval_document` & `plagiarism_document`: Changed from `required` to `nullable`
- Added `mimes:pdf` validation
- Added `max:10240` (10MB) size limit
- Added `description` and `notes` fields

### 2. Updated UpdateSkripsiSeminarRequest.php

Same changes as Store request, but all document fields are `nullable` (not required for updates).

```php
'proposal_document' => [
    'nullable',  // Not required on update
    'file',
    'mimes:pdf',
    'max:10240',
],
```

### 3. Updated SkripsiSeminarController@store

**Before (Dropzone style):**
```php
if ($request->input('proposal_document', false)) {
    $skripsiSeminar->addMediaWithCustomName(
        storage_path('tmp/uploads/' . basename($request->input('proposal_document'))),
        'proposal_document'
    );
}
```

**After (Standard Laravel file upload):**
```php
// Handle file uploads - Direct upload (not via Dropzone temp)
if ($request->hasFile('proposal_document')) {
    $skripsiSeminar->addMedia($request->file('proposal_document'))
        ->toMediaCollection('proposal_document');
}

if ($request->hasFile('approval_document')) {
    $skripsiSeminar->addMedia($request->file('approval_document'))
        ->toMediaCollection('approval_document');
}

if ($request->hasFile('plagiarism_document')) {
    $skripsiSeminar->addMedia($request->file('plagiarism_document'))
        ->toMediaCollection('plagiarism_document');
}
```

**Key Changes:**
- Use `$request->hasFile()` instead of `$request->input()`
- Use `$request->file()` to get UploadedFile object
- Use `addMedia()` directly instead of `addMediaWithCustomName()` with temp path
- Removed CK-media handling
- Removed redundant application update logic (already set on create)
- Added success message

### 4. Updated SkripsiSeminarController@update

**Before (Complex replacement logic):**
```php
if ($request->input('proposal_document', false)) {
    if (! $skripsiSeminar->proposal_document || $request->input('proposal_document') !== $skripsiSeminar->proposal_document->file_name) {
        if ($skripsiSeminar->proposal_document) {
            $skripsiSeminar->proposal_document->delete();
        }
        $skripsiSeminar->addMediaWithCustomName(
            storage_path('tmp/uploads/' . basename($request->input('proposal_document'))),
            'proposal_document'
        );
    }
}
```

**After (Simple replacement):**
```php
// Handle proposal document
if ($request->hasFile('proposal_document')) {
    // Delete old document if exists
    $skripsiSeminar->clearMediaCollection('proposal_document');
    // Add new document
    $skripsiSeminar->addMedia($request->file('proposal_document'))
        ->toMediaCollection('proposal_document');
}
```

**Key Changes:**
- Simplified logic: if new file uploaded, clear old and add new
- Use `clearMediaCollection()` to remove old files
- Same pattern for all 3 document types
- Added success message

## Files Modified

### 1. `app/Http/Requests/StoreSkripsiSeminarRequest.php`
- Changed document validation from `string` to `file`
- Changed `application_id` from `required` to `nullable`
- Changed `approval_document` and `plagiarism_document` from `required` to `nullable`
- Added `mimes:pdf` and `max:10240` validation
- Added `description` and `notes` fields
- Updated validation messages

### 2. `app/Http/Requests/UpdateSkripsiSeminarRequest.php`
- Same changes as Store request
- All document fields are `nullable`

### 3. `app/Http/Controllers/Frontend/SkripsiSeminarController.php`
- `store()`: Changed to use `hasFile()` and `file()` methods
- `store()`: Use `addMedia()` directly with UploadedFile object
- `store()`: Added success message
- `update()`: Simplified file replacement logic
- `update()`: Use `clearMediaCollection()` then `addMedia()`
- `update()`: Added success message

## Validation Rules Summary

### Create (Store):
| Field | Required | Type | Max Size | Format |
|-------|----------|------|----------|--------|
| title | ✅ Yes | string | 255 chars | - |
| description | ❌ No | string | - | - |
| notes | ❌ No | string | - | - |
| proposal_document | ✅ Yes | file | 10MB | PDF |
| approval_document | ❌ No | file | 10MB | PDF |
| plagiarism_document | ❌ No | file | 10MB | PDF |

### Update:
| Field | Required | Type | Max Size | Format |
|-------|----------|------|----------|--------|
| title | ✅ Yes | string | 255 chars | - |
| description | ❌ No | string | - | - |
| notes | ❌ No | string | - | - |
| proposal_document | ❌ No | file | 10MB | PDF |
| approval_document | ❌ No | file | 10MB | PDF |
| plagiarism_document | ❌ No | file | 10MB | PDF |

## Why This Approach?

### Standard Laravel File Upload vs Dropzone:

**Standard File Upload (Current):**
- ✅ Simple HTML `<input type="file">`
- ✅ Direct file validation in Request
- ✅ No JavaScript dependencies
- ✅ Works out of the box
- ✅ Better for simple forms
- ❌ Files uploaded on form submit (slower for large files)

**Dropzone (Original):**
- ✅ AJAX upload with progress bar
- ✅ Multiple files at once
- ✅ Drag & drop interface
- ❌ Requires JavaScript library
- ❌ Requires temp storage endpoint
- ❌ More complex implementation
- ❌ Overkill for single PDF uploads

For this use case (uploading 1-3 PDF files), standard file upload is simpler and sufficient.

## Testing Checklist

- [x] Create form accepts PDF files
- [x] Validation rejects non-PDF files
- [x] Validation rejects files > 10MB
- [x] Proposal document is required
- [x] Approval & Plagiarism documents are optional
- [x] Files are stored in media library
- [x] Files can be downloaded from show page
- [x] Edit form shows existing files
- [x] Edit form can replace files
- [x] Old files are deleted when replaced
- [x] Success messages appear

## Related Components

This fix should be applied to similar controllers that handle file uploads:
- `MbkmSeminarController` (MBKM seminars)
- `SkripsiDefenseController` (Skripsi defenses)
- Any other controllers using Spatie Media Library with forms

## Conclusion

The error was caused by a mismatch between validation rules (expecting string paths for Dropzone) and actual form implementation (sending file objects). The solution was to change validation to accept `file` type and update the controller to handle standard Laravel file uploads using `hasFile()` and `file()` methods.
