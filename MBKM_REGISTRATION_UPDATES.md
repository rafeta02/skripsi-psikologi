# MBKM Registration Updates - Implementation Summary

## Overview
This document outlines the changes made to the MBKM Registration system to implement automatic Application creation, group member management, and file naming standardization.

---

## 1. Auto-Create Application on Registration

### Changes Made:

#### **Model: `app/Models/MbkmRegistration.php`**
- ✅ Added `FileNamingTrait` for standardized file uploads
- ✅ Added `groupMembers()` relationship method
- ✅ Application is now auto-created in the controller (not selected from dropdown)

#### **Controller: `app/Http/Controllers/Frontend/MbkmRegistrationController.php`**
- ✅ **store()**: Creates Application automatically with:
  - `mahasiswa_id`: From authenticated user
  - `type`: 'mbkm'
  - `stage`: 'registration'
  - `status`: 'submitted'
  - `submitted_at`: Current timestamp
- ✅ **store()**: Associates created application_id with MbkmRegistration
- ✅ **create()**: Removed `$applications` variable, added `$mahasiswas` for group members
- ✅ **edit()**: Removed `$applications` variable, added `$mahasiswas` for group members
- ✅ **update()**: Handles group member updates (delete and recreate)
- ✅ **show()**: Loads groupMembers with mahasiswa relationship

---

## 2. File Upload with Custom Naming Trait

### Changes Made:

#### **Model: `app/Models/MbkmRegistration.php`**
```php
use App\Traits\FileNamingTrait;

class MbkmRegistration extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, 
        Auditable, HasFactory, FileNamingTrait;
```

#### **Controller: `app/Http/Controllers/Frontend/MbkmRegistrationController.php`**
All file uploads now use `addMediaWithCustomName()` method:
- ✅ `khs_all` - Multiple files
- ✅ `krs_latest` - Single file
- ✅ `spp` - Single file
- ✅ `proposal_mbkm` - Single file
- ✅ `recognition_form` - Single file

**File Naming Format**: `{application_id}_{collection_name}_{uniqid}.{extension}`

**Example**: `123_khs_all_65a1b2c3d4e5f.pdf`

---

## 3. Group Members Management

### Changes Made:

#### **Model: `app/Models/MbkmRegistration.php`**
```php
public function groupMembers()
{
    return $this->hasMany(MbkmGroupMember::class, 'mbkm_registration_id');
}
```

#### **Model: `app/Models/Application.php`**
```php
public function mbkmRegistration()
{
    return $this->hasOne(MbkmRegistration::class);
}
```

#### **Controller: `app/Http/Controllers/Frontend/MbkmRegistrationController.php`**
- ✅ **store()**: Creates group members from `group_members` array input
- ✅ **update()**: Deletes existing members and recreates from form data
- ✅ **show()**: Loads group members with mahasiswa data for display

#### **Validation: `app/Http/Requests/StoreMbkmRegistrationRequest.php`**
```php
'group_members' => ['array', 'nullable'],
'group_members.*.mahasiswa_id' => ['nullable', 'integer', 'exists:mahasiswas,id'],
'group_members.*.role' => ['nullable', 'in:ketua,anggota'],
```

#### **Validation: `app/Http/Requests/UpdateMbkmRegistrationRequest.php`**
- ✅ Same validation rules as Store request

---

## 4. View Updates

### **Create Form: `resources/views/frontend/mbkmRegistrations/create.blade.php`**

#### Removed:
- ❌ Application dropdown select field

#### Added:
- ✅ Dynamic group member fields section
- ✅ "Add Member" button to add more members
- ✅ "Remove Member" button (hidden when only 1 member)
- ✅ JavaScript for dynamic member management
- ✅ Select2 re-initialization for new fields

**Group Member Fields:**
- Mahasiswa (dropdown) - select from all mahasiswa
- Role (dropdown) - Ketua / Anggota

### **Edit Form: `resources/views/frontend/mbkmRegistrations/edit.blade.php`**

#### Removed:
- ❌ Application dropdown select field

#### Added:
- ✅ Dynamic group member fields (pre-populated if exist)
- ✅ Same JavaScript functionality as create form
- ✅ Proper member index tracking for existing members

### **Show View: `resources/views/frontend/mbkmRegistrations/show.blade.php`**

#### Added:
- ✅ "Anggota Kelompok MBKM" section
- ✅ Table displaying all group members with:
  - No.
  - NIM
  - Nama
  - Role (with badge styling)

---

## 5. JavaScript Functionality

### Dynamic Group Member Management

**Features:**
1. ✅ Add unlimited group members dynamically
2. ✅ Remove members (minimum 1 member maintained)
3. ✅ Auto re-initialize Select2 for new dropdowns
4. ✅ Proper array indexing for form submission
5. ✅ Show/hide remove buttons based on member count

**Code Location:**
- `resources/views/frontend/mbkmRegistrations/create.blade.php` (lines ~676-739)
- `resources/views/frontend/mbkmRegistrations/edit.blade.php` (lines ~277-342)

---

## 6. Database Relations

### MbkmRegistration Relations:
- `application()` - belongsTo Application
- `research_group()` - belongsTo ResearchGroup
- `preference_supervision()` - belongsTo Dosen
- `theme()` - belongsTo Keilmuan
- `created_by()` - belongsTo User
- ✅ **NEW**: `groupMembers()` - hasMany MbkmGroupMember

### Application Relations:
- `mahasiswa()` - belongsTo Mahasiswa
- `actions()` - hasMany ApplicationAction
- `skripsiRegistration()` - hasOne SkripsiRegistration
- ✅ **NEW**: `mbkmRegistration()` - hasOne MbkmRegistration

### MbkmGroupMember Relations (existing):
- `mbkm_registration()` - belongsTo MbkmRegistration
- `mahasiswa()` - belongsTo Mahasiswa

---

## 7. Data Flow

### Registration Process (Store):

```
1. User submits form
   ↓
2. Create Application
   - type: 'mbkm'
   - stage: 'registration'
   - status: 'submitted'
   - mahasiswa_id: auth()->user()->mahasiswa_id
   ↓
3. Create MbkmRegistration
   - application_id: (from step 2)
   - ... other form data
   ↓
4. Create Group Members (loop)
   - mbkm_registration_id: (from step 3)
   - mahasiswa_id: from form
   - role: from form
   ↓
5. Upload Files with Custom Names
   - Format: {application_id}_{collection}_{uniqid}.{ext}
   ↓
6. Redirect to index
```

### Update Process:

```
1. User submits edit form
   ↓
2. Update MbkmRegistration data
   ↓
3. Delete existing group members
   ↓
4. Create new group members from form
   ↓
5. Update files if changed (with custom naming)
   ↓
6. Redirect to index
```

---

## 8. Benefits

### 1. **Simplified Workflow**
- ❌ No need to manually create Application first
- ✅ Automatic Application creation on registration

### 2. **Better File Organization**
- ✅ All files follow standardized naming: `{app_id}_{collection}_{uniqid}.{ext}`
- ✅ Easy to trace which files belong to which application
- ✅ No file name conflicts

### 3. **Group Collaboration Support**
- ✅ Multiple students can be part of MBKM registration
- ✅ Clear role designation (Ketua/Anggota)
- ✅ Easy management of team members

### 4. **Data Integrity**
- ✅ Application always created with correct initial status
- ✅ Proper relationships maintained
- ✅ Validation ensures data consistency

---

## 9. Testing Checklist

### Create Registration:
- [ ] Form loads without application_id dropdown
- [ ] Group member section visible with 1 default member
- [ ] Can add multiple group members
- [ ] Can remove members (minimum 1 maintained)
- [ ] Select2 works on dynamically added members
- [ ] Form submission creates Application automatically
- [ ] Group members are saved correctly
- [ ] Files are uploaded with custom names

### Edit Registration:
- [ ] Form loads with existing group members
- [ ] Can add more members
- [ ] Can remove members
- [ ] Can change existing member data
- [ ] Update saves all changes correctly
- [ ] Old members are deleted and new ones created

### Show Registration:
- [ ] Group members section displays when members exist
- [ ] All member data shown correctly (NIM, Nama, Role)
- [ ] Role badges display correctly (Ketua/Anggota)

### File Uploads:
- [ ] All files named with format: `{app_id}_{collection}_{uniqid}.{ext}`
- [ ] Files can be viewed and downloaded
- [ ] File updates work correctly

---

## 10. Notes

1. **Application ID**: No longer manually selected. Auto-created when registration is submitted.

2. **Group Members**: Optional but fully supported. If no members added, the array will be empty (handled gracefully).

3. **File Naming**: Uses trait so it's consistent across the entire application.

4. **Validation**: Group members validated for:
   - Valid mahasiswa_id (exists in database)
   - Valid role (ketua or anggota only)

5. **JavaScript**: Uses jQuery and Select2 (already present in the project).

---

## 11. Future Enhancements (Optional)

1. **Prevent Duplicate Members**: Add validation to prevent same mahasiswa from being added twice
2. **Role Limit**: Enforce only one "Ketua" per group
3. **Auto-populate Current User**: Automatically add logged-in student as first member
4. **Member Search**: Add search functionality in member dropdown
5. **Bulk Member Import**: Allow importing multiple members from CSV

---

## Files Modified

### Models:
1. ✅ `app/Models/MbkmRegistration.php`
2. ✅ `app/Models/Application.php`

### Controllers:
1. ✅ `app/Http/Controllers/Frontend/MbkmRegistrationController.php`

### Requests:
1. ✅ `app/Http/Requests/StoreMbkmRegistrationRequest.php`
2. ✅ `app/Http/Requests/UpdateMbkmRegistrationRequest.php`

### Views:
1. ✅ `resources/views/frontend/mbkmRegistrations/create.blade.php`
2. ✅ `resources/views/frontend/mbkmRegistrations/edit.blade.php`
3. ✅ `resources/views/frontend/mbkmRegistrations/show.blade.php`

---

## Summary

All requested features have been successfully implemented:
- ✅ Application auto-creation in controller
- ✅ Removed application_id dropdown from forms
- ✅ Added FileNamingTrait for standardized file uploads
- ✅ Added dynamic group member management
- ✅ Multiple members can be added/removed
- ✅ Proper validation and relationships
- ✅ Updated all views (create, edit, show)

The MBKM Registration system is now ready for use!

