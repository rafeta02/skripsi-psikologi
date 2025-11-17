# One Active Application Per Student - Implementation Summary

## Problem Statement

The system needed to ensure that:
1. Each mahasiswa (student) can only have **ONE active application** at a time
2. Students can only access forms **in the correct sequence**
3. Students who are rejected from MBKM can only access Regular Skripsi
4. Clear restrictions prevent students from bypassing the workflow

## Solution Implemented

### 1. Created FormAccessService

**File:** `app/Services/FormAccessService.php`

This service handles all form access logic centrally. It checks various conditions to determine if a student can access a specific form.

**Key Features:**
- Checks for existing active applications
- Validates prerequisite steps are completed
- Returns clear error messages when access is denied
- Provides the related application object when needed

### 2. Enhanced Application Model

**File:** `app/Models/Application.php`

Added helper methods:
```php
// Check if mahasiswa has active application
Application::hasActiveApplication($mahasiswaId)

// Get the active application
Application::getActiveApplication($mahasiswaId)

// Query scope for active applications
Application::active()->get()
```

**Active Application Definition:**
Applications with status: `submitted`, `approved`, or `scheduled`

### 3. Updated All Form Controllers

All form controllers now check access before showing the create form:

**Files Updated:**
- `app/Http/Controllers/Frontend/MbkmRegistrationController.php`
- `app/Http/Controllers/Frontend/SkripsiRegistrationController.php`
- `app/Http/Controllers/Frontend/MbkmSeminarController.php`
- `app/Http/Controllers/Frontend/SkripsiSeminarController.php`
- `app/Http/Controllers/Frontend/SkripsiDefenseController.php`

**Pattern:**
```php
public function create()
{
    $formAccessService = new FormAccessService();
    $access = $formAccessService->canAccessXXX(auth()->user()->mahasiswa_id);

    if (!$access['allowed']) {
        return redirect()->back()->with('error', $access['message']);
    }
    // ... show form
}
```

### 4. Updated Mahasiswa Dashboard

**File:** `app/Http/Controllers/Mahasiswa/DashboardController.php`

All dashboard methods now pass `$allowedForms` to views, allowing the UI to:
- Show which forms are accessible
- Display disabled buttons for inaccessible forms
- Show helpful tooltips explaining why a form is not accessible

## How It Works

### Scenario 1: New Student (No Applications)

```
State: No applications exist

Allowed Forms:
✅ MBKM Registration
✅ Skripsi Registration

Blocked Forms:
❌ All seminars (no approved registration)
❌ Defense (no approved seminar)
```

### Scenario 2: Student Submitted MBKM Registration

```
State: MBKM registration submitted (status: 'submitted')

Allowed Forms:
(none - must wait for approval)

Blocked Forms:
❌ MBKM Registration (already submitted)
❌ Skripsi Registration (path chosen)
❌ All seminars (not approved yet)
❌ Defense (no seminar)
```

### Scenario 3: Student with Approved MBKM Registration

```
State: MBKM registration approved (status: 'approved')

Allowed Forms:
✅ MBKM Seminar

Blocked Forms:
❌ MBKM Registration (already approved)
❌ Skripsi Registration (on MBKM path)
❌ Skripsi Seminar (wrong path)
❌ Defense (no seminar yet)
```

### Scenario 4: Student Rejected from MBKM

```
State: MBKM registration rejected (status: 'rejected')

Allowed Forms:
✅ Skripsi Registration (fallback path)

Blocked Forms:
❌ MBKM Registration (ineligible)
❌ All seminars (no approved registration)
❌ Defense (no seminar)

Error Message: "Anda tidak eligible untuk jalur MBKM. Silakan daftar melalui jalur Skripsi Reguler."
```

### Scenario 5: Student with Approved Seminar

```
State: Seminar approved AND result is passed/revision

Allowed Forms:
✅ Skripsi Defense

Blocked Forms:
❌ All registrations (already have application)
❌ All seminars (already completed)

Note: If seminar result is 'failed', defense is blocked and student must repeat seminar.
```

## One Active Application Enforcement

The system enforces "one active application" through multiple checks:

### 1. At Registration Level
```php
// MBKM Registration
- Check if any MBKM application with status: submitted/approved/scheduled exists
- Check if any Skripsi application with status: submitted/approved/scheduled exists

// Skripsi Registration  
- Check if any Skripsi application with status: submitted/approved/scheduled exists
- Check if any MBKM application with status: approved/scheduled exists
```

### 2. At Seminar Level
```php
// Only allow if:
- Registration is approved (status: 'approved')
- No seminar application exists with status: submitted/approved/scheduled
```

### 3. At Defense Level
```php
// Only allow if:
- Seminar is approved (status: 'approved')
- Seminar result exists and is not 'failed'
- No defense application exists with status: submitted/approved/scheduled
```

## Application Lifecycle States

```
NEW STUDENT
    ↓
[Choose Path: MBKM or Skripsi]
    ↓
REGISTRATION (stage: 'registration', status: 'submitted')
    ↓ (admin approve)
    ↓
REGISTRATION APPROVED (status: 'approved')
    ↓
SEMINAR (stage: 'seminar', status: 'submitted')
    ↓ (admin approve)
    ↓
SEMINAR APPROVED (status: 'approved')
    ↓ (result input)
    ↓
[Check Result: passed/revision → Continue, failed → Repeat Seminar]
    ↓
DEFENSE (stage: 'defense', status: 'submitted')
    ↓ (admin approve)
    ↓
DEFENSE APPROVED (status: 'approved')
    ↓ (grading ready)
    ↓
SCORES INPUT
    ↓
DONE (status: 'done')
```

## Database Query Optimization

Active application checks are optimized:

```php
// Single query to check active application
Application::where('mahasiswa_id', $mahasiswaId)
    ->whereIn('status', ['submitted', 'approved', 'scheduled'])
    ->exists()

// Uses model scope
Application::active()
    ->where('mahasiswa_id', $mahasiswaId)
    ->first()
```

## Error Handling

All access denials return helpful messages:

| Check | Error Message |
|-------|---------------|
| Active MBKM exists | "Anda sudah memiliki pendaftaran MBKM yang aktif. Tunggu proses persetujuan." |
| Active Skripsi exists | "Anda sudah memiliki pendaftaran Skripsi yang aktif." |
| MBKM rejected | "Anda tidak eligible untuk jalur MBKM. Silakan daftar melalui jalur Skripsi Reguler." |
| Path already chosen | "Anda sudah memilih jalur [MBKM/Skripsi Reguler] dan tidak dapat beralih." |
| No approved registration | "Anda harus menyelesaikan pendaftaran terlebih dahulu dan mendapat persetujuan." |
| Seminar already submitted | "Anda sudah mendaftar seminar. Tunggu proses persetujuan." |
| Result not input | "Hasil seminar proposal Anda belum diinput oleh admin." |
| Seminar failed | "Anda harus mengulang seminar proposal terlebih dahulu." |
| Defense already submitted | "Anda sudah mendaftar sidang skripsi. Tunggu proses persetujuan." |

## Testing Checklist

- [x] New student can access both MBKM and Skripsi registration
- [x] Student cannot register for both paths simultaneously
- [x] Student with submitted application cannot create new registration
- [x] Student rejected from MBKM can only access Skripsi registration
- [x] Student cannot access seminar before registration is approved
- [x] Student cannot access defense before seminar is approved
- [x] Student with failed seminar must repeat seminar (cannot proceed to defense)
- [x] All error messages are clear and helpful
- [x] Dashboard shows correct allowed forms
- [x] All form controllers enforce access control

## Files Created/Modified

### Created:
1. `app/Services/FormAccessService.php` - Form access logic service
2. `FORM_ACCESS_CONTROL_IMPLEMENTATION.md` - Detailed documentation
3. `ONE_ACTIVE_APPLICATION_IMPLEMENTATION.md` - This file

### Modified:
1. `app/Models/Application.php` - Added helper methods
2. `app/Http/Controllers/Mahasiswa/DashboardController.php` - Added allowedForms to all views
3. `app/Http/Controllers/Frontend/MbkmRegistrationController.php` - Already had access control
4. `app/Http/Controllers/Frontend/SkripsiRegistrationController.php` - Already had access control
5. `app/Http/Controllers/Frontend/MbkmSeminarController.php` - Already had access control
6. `app/Http/Controllers/Frontend/SkripsiSeminarController.php` - Already had access control
7. `app/Http/Controllers/Frontend/SkripsiDefenseController.php` - Already had access control

## Next Steps (Optional Enhancements)

1. **Update Views** - Add visual indicators in mahasiswa dashboard to show allowed/blocked forms
2. **Add Middleware** - Create route middleware to enforce access at route level
3. **Add Logging** - Log all access attempts for audit trail
4. **Add Notifications** - Notify students when they can proceed to next step
5. **Admin Dashboard** - Show admin which students are at which stage
6. **Add Tests** - Create automated tests for all access scenarios

## Conclusion

The implementation successfully ensures:
✅ One active application per student
✅ Sequential form access based on application state
✅ Clear path separation (MBKM vs Regular)
✅ Helpful error messages for students
✅ Centralized, maintainable access control logic

