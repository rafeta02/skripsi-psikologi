# MBKM Registration - File Upload Validation Update

## Overview
Semua file upload pada form MBKM Registration kini menjadi **REQUIRED/WAJIB**.

---

## File yang Wajib Diupload

### 1. **Dokumen Akademik**
- ✅ **KHS Semua Semester** (khs_all) - Multiple files
- ✅ **KRS Semester Terakhir** (krs_latest) - Single file
- ✅ **Bukti Pembayaran SPP** (spp) - Single file

### 2. **Dokumen MBKM**
- ✅ **Proposal MBKM** (proposal_mbkm) - Single file
- ✅ **Form Konversi SKS** (recognition_form) - Single file

---

## Validation Rules

### StoreMbkmRegistrationRequest.php

```php
'khs_all' => [
    'required',
    'array',
],
'khs_all.*' => [
    'required',
],
'krs_latest' => [
    'required',
],
'spp' => [
    'required',
],
'proposal_mbkm' => [
    'required',
],
'recognition_form' => [
    'required',
],
```

### UpdateMbkmRegistrationRequest.php

```php
// Same validation rules as Store
'khs_all' => [
    'required',
    'array',
],
'khs_all.*' => [
    'required',
],
'krs_latest' => [
    'required',
],
'spp' => [
    'required',
],
'proposal_mbkm' => [
    'required',
],
'recognition_form' => [
    'required',
],
```

---

## Perubahan Visual di Form

### 1. Label dengan Tanda Bintang Merah (*)
Semua label file upload kini memiliki indikator `<span class="text-danger">*</span>`:

```blade
<label for="khs_all">KHS Semua Semester <span class="text-danger">*</span></label>
<label for="krs_latest">KRS Semester Terakhir <span class="text-danger">*</span></label>
<label for="spp">Bukti Pembayaran SPP <span class="text-danger">*</span></label>
<label for="proposal_mbkm">Proposal MBKM <span class="text-danger">*</span></label>
<label for="recognition_form">Form Konversi SKS <span class="text-danger">*</span></label>
```

### 2. Info Box Update
Info box di bagian atas form diupdate dengan informasi:

```html
<li><strong>Semua dokumen WAJIB diupload</strong> (bertanda <span class="text-danger">*</span>)</li>
```

---

## Error Messages

Jika user mencoba submit form tanpa upload file, akan muncul error validation:

- **khs_all**: "The khs all field is required."
- **krs_latest**: "The krs latest field is required."
- **spp**: "The spp field is required."
- **proposal_mbkm**: "The proposal mbkm field is required."
- **recognition_form**: "The recognition form field is required."

---

## Files Modified

1. ✅ `app/Http/Requests/StoreMbkmRegistrationRequest.php`
   - Added `required` validation for all file fields

2. ✅ `app/Http/Requests/UpdateMbkmRegistrationRequest.php`
   - Added `required` validation for all file fields

3. ✅ `resources/views/frontend/mbkmRegistrations/create.blade.php`
   - Added red asterisk (*) to all file upload labels
   - Updated info box message

---

## Testing Checklist

### Create Form:
- [ ] Try submit without any files → Should show validation errors
- [ ] Try submit with only some files → Should show errors for missing files
- [ ] Try submit with all files → Should succeed
- [ ] Check that red asterisk (*) appears on all file labels
- [ ] Check that info box shows "WAJIB diupload" message

### Edit Form:
- [ ] Existing files should be displayed
- [ ] Try update without re-uploading files → Should show validation errors
- [ ] Try update with all files → Should succeed
- [ ] All file fields should show red asterisk (*)

---

## Notes

1. **KHS All (Multiple Files)**: 
   - Array harus ada dan tidak boleh kosong
   - Setiap file dalam array juga required

2. **Single File Fields**:
   - krs_latest, spp, proposal_mbkm, recognition_form
   - Semua harus diupload

3. **File Format**:
   - Semua file harus dalam format PDF
   - Maksimal ukuran: 10 MB per file

4. **File Naming**:
   - Tetap menggunakan FileNamingTrait
   - Format: `{application_id}_{collection_name}_{uniqid}.{extension}`

---

## Kesimpulan

✅ Validasi required sudah diterapkan pada semua file upload  
✅ Visual indicator (tanda *) sudah ditambahkan  
✅ Info box sudah diupdate  
✅ Validation rules sama untuk Create dan Update  
✅ Tidak ada linter errors  

Mahasiswa sekarang WAJIB melengkapi semua dokumen persyaratan sebelum dapat mendaftar MBKM!

