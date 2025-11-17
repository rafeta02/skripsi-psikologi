# Skripsi Defense - Multi-Step Form with Validation

## Overview
Form pendaftaran Sidang Skripsi telah diupdate dengan **Multi-Step Wizard** yang lebih user-friendly dan terstruktur. Semua dokumen (15 required + 2 optional) harus diisi sebelum form bisa di-submit.

## Features Implemented

### 1. Multi-Step Form Structure
Form dibagi menjadi **6 langkah (steps)**:

#### **Step 1: Informasi Dasar**
- Judul Skripsi (required)
- Abstrak (required)

#### **Step 2: Dokumen Utama**
- Dokumen Sidang (required, max 25MB)
- Laporan Plagiarisme (required, max 10MB)

#### **Step 3: Dokumen Etika & Penelitian**
- Pernyataan Etika Penelitian (required, multiple files)
- Instrumen Penelitian (required, multiple files)
- Surat Izin Pengumpulan Data (required, multiple files)
- Modul Penelitian (required, multiple files)

#### **Step 4: Dokumen Akademik & Administrasi**
- Bukti Pembayaran SPP (required, max 10MB)
- KRS Terbaru (required, max 10MB)
- Sertifikat EAP (required, max 10MB)
- Transkrip Nilai (required, max 10MB)

#### **Step 5: Dokumen Pendukung**
- Surat Rekomendasi MBKM (optional, max 10MB)
- Pernyataan Publikasi (required, max 10MB)
- Halaman Persetujuan Sidang (required, multiple files)
- Laporan MBKM (optional, multiple files)
- Poster Penelitian (required, multiple files)
- Screenshot Pembimbing SIAKAD (required, max 10MB)
- Logbook Bimbingan (required, multiple files)

#### **Step 6: Review & Submit**
- Ringkasan data yang diisi
- Checklist dokumen yang sudah diupload
- Warning jika ada dokumen yang belum lengkap
- Submit button (disabled jika dokumen belum lengkap)

### 2. Visual Design

#### Progress Indicator
```
[1]─────[2]─────[3]─────[4]─────[5]─────[6]
Active   Next    Next    Next    Next    Next
```

**Status Colors:**
- Gray: Belum dikunjungi
- Blue: Step aktif (sedang di isi)
- Green: Step completed (sudah diisi)

#### Step Circle
- Number indicator (1-6)
- Label di bawah (nama step)
- Progress line connecting circles

#### Document Groups
- Grouped by category in colored boxes
- Clear labels with required/optional indicator
- Help text showing file size limits

### 3. JavaScript Features

#### Navigation
```javascript
// Next Button
- Validates current step before proceed
- Shows next step with animation
- Updates progress indicator

// Previous Button
- Goes back to previous step
- No validation required
- Hidden on step 1

// Submit Button
- Only shown on step 6
- Disabled if documents incomplete
- Final validation before submit
```

#### Validation Logic

**Step 1 Validation:**
```javascript
- Check if title is filled
- Check if abstract is filled
- Alert if empty
```

**Step 2-5 Validation:**
```javascript
- Check if all required documents uploaded
- Track upload status in uploadedFiles object
- Alert if any required document missing
```

**Step 6 Validation:**
```javascript
- Show complete checklist
- Count missing documents
- Display warning with list of missing docs
- Disable submit button if incomplete
```

#### File Upload Tracking
```javascript
const uploadedFiles = {
    defence_document: false,
    plagiarism_report: false,
    ethics_statement: false,
    // ... all 17 documents
}

// Updated on:
- File upload success → true
- File removed → false (or check remaining count for multiple files)
```

### 4. Backend Validation
**File**: `app/Http/Requests/StoreSkripsiDefenseRequest.php`

#### Required Fields:
```php
'application_id' => 'required|exists:applications,id',
'title' => 'required|string',
'abstract' => 'required|string',

// Single file documents (8 required)
'defence_document' => 'required',
'plagiarism_report' => 'required',
'publication_statement' => 'required',
'spp_receipt' => 'required',
'krs_latest' => 'required',
'eap_certificate' => 'required',
'transcript' => 'required',
'siakad_supervisor_screenshot' => 'required',

// Multiple file documents (7 required)
'ethics_statement' => 'required|array|min:1',
'research_instruments' => 'required|array|min:1',
'data_collection_letter' => 'required|array|min:1',
'research_module' => 'required|array|min:1',
'defense_approval_page' => 'required|array|min:1',
'research_poster' => 'required|array|min:1',
'supervision_logbook' => 'required|array|min:1',

// Optional documents
'mbkm_recommendation_letter' => 'nullable',
'mbkm_report' => 'nullable|array',
```

#### Custom Error Messages:
```php
public function messages()
{
    return [
        'defence_document.required' => 'Dokumen sidang harus diupload',
        'ethics_statement.required' => 'Pernyataan etika penelitian harus diupload minimal 1 file',
        // ... custom messages for all fields
    ];
}
```

### 5. CSS Styling

#### Multi-Step Container
```css
.multi-step-form {
    position: relative;
}
```

#### Step Indicator
```css
.step-indicator {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0; /* Default */
}

.step.active .step-circle {
    background: #007bff; /* Blue */
}

.step.completed .step-circle {
    background: #28a745; /* Green */
}
```

#### Form Steps
```css
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
```

#### Document Groups
```css
.document-group {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
```

#### Dropzone
```css
.dropzone {
    border: 2px dashed #007bff;
    border-radius: 5px;
    background: #f8f9fa;
    transition: all 0.3s;
}

.dropzone:hover {
    border-color: #0056b3;
    background: #e7f3ff;
}

.dropzone.dz-started {
    border-color: #28a745; /* Green when file uploaded */
}
```

### 6. User Experience

#### Workflow
```
1. Mahasiswa opens create defense form
   ↓
2. Sees progress indicator (6 steps)
   ↓
3. Step 1: Fills title & abstract
   - Next button validates inputs
   ↓
4. Step 2-5: Uploads documents
   - Each step validated before next
   - Visual feedback on upload success
   - Can go back to edit previous steps
   ↓
5. Step 6: Reviews everything
   - See summary of inputted data
   - See checklist of uploaded documents
   - Warning if anything missing
   ↓
6. Submit disabled if incomplete
   ↓
7. Submit enabled when all required docs uploaded
   ↓
8. Backend validates again before storing
```

#### Visual Feedback

**Upload Success:**
- Dropzone border turns green
- File thumbnail shown
- uploadedFiles[key] = true

**Upload Error:**
- Red error message shown
- File not added to form
- uploadedFiles[key] = false

**Step Validation:**
- Alert popup if validation fails
- Cannot proceed to next step
- Clear message about what's missing

**Review Page:**
- ✅ Green check: Document uploaded
- ❌ Red X: Required document missing
- ⭕ Gray circle: Optional document not uploaded

### 7. Dropzone Implementation

#### Single File Upload
```javascript
Dropzone.options.defenceDocumentDropzone = {
    url: '{{ route('frontend.skripsi-defenses.storeMedia') }}',
    maxFilesize: 25, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    success: function (file, response) {
        $('form').append('<input type="hidden" name="defence_document" value="' + response.name + '">');
        uploadedFiles.defence_document = true;
    },
    removedfile: function (file) {
        $('form').find('input[name="defence_document"]').remove();
        uploadedFiles.defence_document = false;
    }
}
```

#### Multiple File Upload
```javascript
var uploadedEthicsStatementMap = {}
Dropzone.options.ethicsStatementDropzone = {
    url: '{{ route('frontend.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    success: function (file, response) {
        $('form').append('<input type="hidden" name="ethics_statement[]" value="' + response.name + '">');
        uploadedEthicsStatementMap[file.name] = response.name;
        uploadedFiles.ethics_statement = true;
    },
    removedfile: function (file) {
        var name = uploadedEthicsStatementMap[file.name];
        $('form').find('input[name="ethics_statement[]"][value="' + name + '"]').remove();
        
        const remaining = $('form').find('input[name="ethics_statement[]"]').length;
        uploadedFiles.ethics_statement = remaining > 0;
    }
}
```

### 8. Review Page Features

#### Data Summary
```html
<table class="table">
    <tr>
        <th>Judul</th>
        <td id="review-title">-</td>
    </tr>
    <tr>
        <th>Abstrak</th>
        <td id="review-abstract">-</td>
    </tr>
</table>
```

#### Document Checklist
```javascript
function updateReviewPage() {
    // Update basic info
    $('#review-title').text($('#title').val());
    $('#review-abstract').text($('#abstract').val());
    
    // Generate checklist
    for (const [key, label] of Object.entries(documentLabels)) {
        const isRequired = requiredDocuments.includes(key);
        const isUploaded = uploadedFiles[key];
        
        // Show icon based on status
        if (isUploaded) {
            icon = 'check-circle text-success';
        } else if (isRequired) {
            icon = 'times-circle text-danger';
            missingDocs.push(label);
        } else {
            icon = 'circle text-secondary';
        }
    }
    
    // Disable submit if documents missing
    if (missingDocs.length > 0) {
        $('#submitBtn').prop('disabled', true);
        $('#validation-warning').show();
    } else {
        $('#submitBtn').prop('disabled', false);
        $('#validation-warning').hide();
    }
}
```

### 9. File Structure

```
resources/views/frontend/skripsiDefenses/
├── create.blade.php              # Main multi-step form
├── edit.blade.php                # Edit form (simple version)
├── show.blade.php                # View defense
├── index.blade.php               # List defenses
└── partials/
    └── dropzone-scripts.blade.php  # Dropzone initialization scripts
```

### 10. Benefits

#### For Students:
- ✅ **Clear Progress**: Know exactly where they are in the process
- ✅ **Organized**: Documents grouped logically by category
- ✅ **Validation**: Cannot skip required steps
- ✅ **Visual Feedback**: Clear indication of upload success
- ✅ **Review**: Can review all data before submitting
- ✅ **Error Prevention**: Submit disabled if incomplete

#### For Admin:
- ✅ **Complete Data**: All required documents guaranteed
- ✅ **Validation**: Backend double-checks everything
- ✅ **Less Errors**: Students cannot submit incomplete forms
- ✅ **Quality**: Better data quality from structured input

#### For System:
- ✅ **UX Improvement**: Much better than long single-page form
- ✅ **Reduced Errors**: Validation at each step
- ✅ **Data Integrity**: All required fields enforced
- ✅ **Professional**: Modern, professional appearance

### 11. Technical Specifications

#### Browser Compatibility:
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Responsive design (mobile-friendly)

#### Dependencies:
- jQuery
- Dropzone.js
- Bootstrap 4
- Font Awesome (icons)

#### File Size Limits:
- Defence Document: 25MB
- All other documents: 10MB per file
- No limit on number of files for multiple uploads

#### Supported File Types:
- PDF (recommended for all documents)
- JPG/PNG (for screenshots and certificates)

### 12. Testing Checklist

#### Navigation:
- [ ] Can navigate forward through all steps
- [ ] Can navigate backward through all steps
- [ ] Step 1 validation works (title & abstract required)
- [ ] Step 2-5 validation works (documents required)
- [ ] Cannot skip steps by clicking indicator
- [ ] Next button hidden on step 6
- [ ] Previous button hidden on step 1
- [ ] Submit button only shown on step 6

#### File Uploads:
- [ ] Can upload single files
- [ ] Can upload multiple files
- [ ] Can remove uploaded files
- [ ] Upload status tracked correctly
- [ ] File size limits enforced
- [ ] Error messages shown on upload failure

#### Validation:
- [ ] Frontend validation prevents proceeding with empty fields
- [ ] Review page shows all uploaded documents
- [ ] Missing documents listed in warning
- [ ] Submit button disabled when documents missing
- [ ] Submit button enabled when all documents uploaded
- [ ] Backend validation catches any missed frontend validation

#### Visual:
- [ ] Progress indicator shows correct status
- [ ] Step circles change color correctly
- [ ] Animations smooth
- [ ] Dropzone visual feedback works
- [ ] Required field markers (*) shown
- [ ] Help text displayed
- [ ] Responsive on mobile

#### Functionality:
- [ ] Form submits successfully
- [ ] All data saved correctly
- [ ] All files uploaded with custom names
- [ ] Redirects to index after submit
- [ ] Error messages shown on validation failure

### 13. Future Enhancements

#### Possible Improvements:
1. **Save Draft**: Allow saving incomplete form as draft
2. **Auto-save**: Automatically save progress periodically
3. **File Preview**: Show preview of uploaded PDFs/images
4. **Drag & Drop**: Enhanced drag-and-drop for files
5. **Progress Percentage**: Show percentage complete
6. **Estimated Time**: Show estimated time to complete
7. **Help Tooltips**: Contextual help for each field
8. **Keyboard Navigation**: Arrow keys to navigate steps
9. **Mobile Optimization**: Better mobile upload experience
10. **Bulk Upload**: Upload multiple files at once

## Summary

Form pendaftaran Sidang Skripsi sekarang menggunakan **Multi-Step Wizard** yang:

✅ **User-Friendly**: 6 langkah terstruktur yang mudah diikuti
✅ **Validated**: Validasi di setiap langkah + final validation
✅ **Visual**: Progress indicator, animations, color coding
✅ **Complete**: Memastikan semua 15 dokumen required terisi
✅ **Professional**: Design modern dan responsive
✅ **Error-Free**: Prevent submission of incomplete forms

Mahasiswa sekarang dapat dengan mudah melengkapi semua persyaratan sidang skripsi dengan guidance yang jelas di setiap langkah! 🎓✨

