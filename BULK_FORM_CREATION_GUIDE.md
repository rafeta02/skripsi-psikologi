# Bulk Form Creation Guide

## 📋 Progress Status

### ✅ Completed Files (12 files):
1. ✓ applicationReports/create.blade.php
2. ✓ applicationSchedules/create.blade.php
3. ✓ applicationSchedules/edit.blade.php
4. ✓ applicationSchedules/index.blade.php
5. ✓ applicationSchedules/show.blade.php
6. ✓ applicationResultSeminars/create.blade.php
7. ✓ applicationResultSeminars/edit.blade.php
8. ✓ applicationResultSeminars/index.blade.php
9. ✓ applicationResultSeminars/show.blade.php
10. ✓ applicationResultDefenses/create.blade.php
11. ✓ public/css/modern-form.css (Shared CSS)
12. ✓ FORM_REDESIGN_GUIDE.md (Templates & Guide)

### 🔄 Remaining Files (19 files):

#### Application Result Defenses (3 files):
- applicationResultDefenses/edit.blade.php
- applicationResultDefenses/index.blade.php
- applicationResultDefenses/show.blade.php

#### MBKM Registrations (4 files):
- mbkmRegistrations/create.blade.php
- mbkmRegistrations/edit.blade.php
- mbkmRegistrations/index.blade.php
- mbkmRegistrations/show.blade.php

#### MBKM Seminars (4 files):
- mbkmSeminars/create.blade.php
- mbkmSeminars/edit.blade.php
- mbkmSeminars/index.blade.php
- mbkmSeminars/show.blade.php

#### Skripsi Seminars (4 files):
- skripsiSeminars/create.blade.php
- skripsiSeminars/edit.blade.php
- skripsiSeminars/index.blade.php
- skripsiSeminars/show.blade.php

#### Skripsi Defenses (4 files):
- skripsiDefenses/create.blade.php
- skripsiDefenses/edit.blade.php
- skripsiDefenses/index.blade.php
- skripsiDefenses/show.blade.php

## 🎯 Quick Implementation Strategy

All remaining files follow the same pattern as the completed ones. Use these reference files:

### For CREATE forms:
- **Reference**: `applicationResultSeminars/create.blade.php` or `applicationResultDefenses/create.blade.php`
- **Pattern**: Form card with header, body sections, dropzones for file uploads

### For EDIT forms:
- **Reference**: `applicationResultSeminars/edit.blade.php`
- **Pattern**: Same as create, but with pre-filled data and warning info box

### For INDEX pages:
- **Reference**: `applicationResultSeminars/index.blade.php` or `applicationSchedules/index.blade.php`
- **Pattern**: Page header, table card with DataTables, status badges

### For SHOW pages:
- **Reference**: `applicationResultSeminars/show.blade.php` or `applicationSchedules/show.blade.php`
- **Pattern**: Detail card with sections, document lists, info items

## 🔧 Key Components to Include

### 1. CSS Link (All files):
```blade
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
```

### 2. Form Structure (Create/Edit):
```blade
<div class="form-card">
    <div class="form-header">
        <h2>Title</h2>
        <p>Subtitle</p>
    </div>
    <div class="form-body">
        <!-- Form fields here -->
    </div>
    <div class="form-actions">
        <a href="..." class="btn-back">...</a>
        <button type="submit" class="btn-submit">...</button>
    </div>
</div>
```

### 3. Index Structure:
```blade
<div class="page-header">...</div>
<div class="table-card">
    <table class="table ...">...</table>
</div>
```

### 4. Show Structure:
```blade
<div class="detail-card">
    <div class="detail-header">...</div>
    <div class="detail-body">
        <div class="detail-section">...</div>
    </div>
    <div class="detail-actions">...</div>
</div>
```

## 🎨 Color Scheme (Base: #22004C)

All forms use the purple gradient:
- Primary: #22004C
- Secondary: #4A0080
- Gradient: `linear-gradient(135deg, #22004C 0%, #4A0080 100%)`

## 📝 Field Mappings

### MBKM Registrations:
- application_id (select2)
- research_group_id (select2)
- preference_supervision_id (select2)
- theme_id (select2)
- title_mbkm (text)
- title (text)
- khs_all (dropzone - multiple)
- khs_last (dropzone - single)
- transcript (dropzone - single)
- research_proposal (dropzone - single)
- recommendation_letter (dropzone - multiple)
- certificate (dropzone - multiple)

### MBKM Seminars:
- application_id (select2)
- title (text)
- proposal_document (dropzone - single)
- approval_document (dropzone - single)
- plagiarism_document (dropzone - single)

### Skripsi Seminars:
- application_id (select2)
- title (text)
- proposal_document (dropzone - single)
- approval_document (dropzone - single)
- plagiarism_document (dropzone - single)

### Skripsi Defenses:
- application_id (select2)
- title (text)
- abstract (text)
- defence_document (dropzone - single)
- plagiarism_report (dropzone - single)
- ethics_statement (dropzone - single)
- research_instruments (dropzone - multiple)
- data_collection_letter (dropzone - multiple)
- certificate (dropzone - multiple)
- logbook (dropzone - single)
- journal_publication (dropzone - multiple)

### Application Result Defenses:
- application_id (select2)
- result (select - passed/revision/failed)
- note (textarea)
- revision_deadline (date)
- report_document (dropzone - multiple)
- attendance_document (dropzone - single)
- form_document (dropzone - multiple)
- latest_script (dropzone - single)
- documentation (dropzone - multiple)
- certificate_document (dropzone - single)
- publication_document (dropzone - multiple)

## ⚡ Automation Tips

### Using VS Code Multi-Cursor:
1. Open reference file (e.g., `applicationResultSeminars/create.blade.php`)
2. Copy entire content
3. Open new file
4. Paste and use Find & Replace:
   - Replace route names: `application-result-seminars` → `mbkm-registrations`
   - Replace model names: `ApplicationResultSeminar` → `MbkmRegistration`
   - Replace variable names: `$applicationResultSeminar` → `$mbkmRegistration`
   - Update titles and descriptions
   - Adjust form fields according to the field mappings above

### Dropzone Pattern:
```javascript
Dropzone.options.fieldNameDropzone = {
    url: '{{ route('frontend.module-name.storeMedia') }}',
    maxFilesize: 10, // or 25 for larger files
    maxFiles: 1, // or remove for multiple
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10 // or 25
    },
    success: function (file, response) {
      // For single file:
      $('form').find('input[name="field_name"]').remove()
      $('form').append('<input type="hidden" name="field_name" value="' + response.name + '">')
      
      // For multiple files:
      $('form').append('<input type="hidden" name="field_name[]" value="' + response.name + '">')
      uploadedFieldNameMap[file.name] = response.name
    },
    removedfile: function (file) {
      // Handle file removal
    },
    init: function () {
      // Handle existing files for edit mode
    },
    error: function (file, response) {
      // Handle errors
    }
}
```

## 🚀 Recommended Workflow

### Option 1: Manual (Recommended for Quality)
1. Start with one module (e.g., applicationResultDefenses)
2. Create all 4 files (create, edit, index, show) for that module
3. Test the module
4. Move to next module
5. Repeat

### Option 2: Batch Creation (Faster)
1. Create all CREATE files first (copy from reference, adjust fields)
2. Create all EDIT files (copy from create, add edit-specific code)
3. Create all INDEX files (simpler, mostly table structure)
4. Create all SHOW files (display data, no forms)

## 📦 File Size Reference

- Simple forms (3-4 fields): ~200-300 lines
- Complex forms (10+ fields): ~500-700 lines
- Index pages: ~150-200 lines
- Show pages: ~200-300 lines

## ✅ Checklist Before Completion

For each file, ensure:
- [ ] CSS link included
- [ ] Modern design classes applied
- [ ] Purple color scheme (#22004C)
- [ ] Responsive layout
- [ ] Status badges (where applicable)
- [ ] Document icons and previews
- [ ] Proper route names
- [ ] Error handling
- [ ] Help text for fields
- [ ] Back and submit buttons
- [ ] Dropzone configuration (for forms)
- [ ] DataTables configuration (for index)

## 🎓 Notes

- All files use the same modern-form.css
- Consistency is key - follow the established patterns
- Test each module after completion
- The design is already proven and working in completed files
- Focus on field mappings and route names - the rest is template work

## 📞 Need Help?

Refer to:
1. `FORM_REDESIGN_GUIDE.md` - Detailed templates
2. `public/css/modern-form.css` - All available CSS classes
3. Completed files in `applicationResultSeminars/` - Working examples
4. Completed files in `applicationSchedules/` - Working examples

---

**Total Remaining Work**: 19 files
**Estimated Time**: 2-4 hours (depending on method)
**Difficulty**: Easy (template-based work)
