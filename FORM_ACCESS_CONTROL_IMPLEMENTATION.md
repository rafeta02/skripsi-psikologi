# Form Access Control Implementation

This document explains the sequential form access control system implemented to ensure students can only access forms in the correct sequence based on their application status.

## Overview

The system ensures:
1. **One Active Application per Student** - Each student can only have one active application at a time
2. **Sequential Form Access** - Students can only access forms in the correct order
3. **Path-Specific Rules** - Different rules apply for MBKM and Regular Skripsi paths
4. **Clear Error Messages** - Students receive helpful messages when they cannot access a form

## Implementation Components

### 1. FormAccessService (`app/Services/FormAccessService.php`)

This service centralizes all form access logic and provides methods to check if a student can access specific forms.

#### Available Methods:

- **`canAccessMbkmRegistration($mahasiswaId)`** - Checks if student can register for MBKM path
- **`canAccessSkripsiRegistration($mahasiswaId)`** - Checks if student can register for Regular Skripsi path
- **`canAccessMbkmSeminar($mahasiswaId)`** - Checks if student can register for MBKM seminar
- **`canAccessSkripsiSeminar($mahasiswaId)`** - Checks if student can register for Skripsi seminar
- **`canAccessSkripsiDefense($mahasiswaId)`** - Checks if student can register for defense/sidang
- **`getAllowedForms($mahasiswaId)`** - Returns an array of all form access permissions
- **`hasActiveApplication($mahasiswaId)`** - Checks if student has an active application
- **`getActiveApplication($mahasiswaId)`** - Gets the active application for a student

#### Return Format:

Each access check method returns an array:
```php
[
    'allowed' => true|false,      // Whether access is granted
    'message' => 'Error message', // Explanation when access is denied
    'application' => $app         // Related application (if applicable)
]
```

### 2. Application Model Updates (`app/Models/Application.php`)

Added helper methods to the Application model:

```php
// Static method to check if mahasiswa has active application
Application::hasActiveApplication($mahasiswaId)

// Static method to get active application
Application::getActiveApplication($mahasiswaId)

// Scope to filter active applications
Application::active()->get()
```

**Active Application Statuses:**
- `submitted` - Application has been submitted
- `approved` - Application has been approved
- `scheduled` - Application has a schedule

### 3. Controller Integration

All form controllers now check access before displaying the create form:

**Example from `MbkmRegistrationController`:**
```php
public function create()
{
    abort_if(Gate::denies('mbkm_registration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    // Check if student can access this form
    $formAccessService = new FormAccessService();
    $access = $formAccessService->canAccessMbkmRegistration(auth()->user()->mahasiswa_id);

    if (!$access['allowed']) {
        return redirect()->route('frontend.mbkm-registrations.index')
            ->with('error', $access['message']);
    }

    // Continue with form display...
}
```

**Updated Controllers:**
- ✅ `Frontend\MbkmRegistrationController`
- ✅ `Frontend\SkripsiRegistrationController`
- ✅ `Frontend\MbkmSeminarController`
- ✅ `Frontend\SkripsiSeminarController`
- ✅ `Frontend\SkripsiDefenseController`

### 4. Mahasiswa Dashboard Integration (`app/Http/Controllers/Mahasiswa/DashboardController.php`)

The dashboard now passes `$allowedForms` to all views, which can be used to show/hide form buttons.

**All Dashboard Methods Updated:**
- `index()` - Main dashboard
- `aplikasi()` - Applications page
- `bimbingan()` - Supervision page
- `jadwal()` - Schedule page
- `dokumen()` - Documents page
- `profile()` - Profile page

## Access Rules

### MBKM Registration (`/frontend/mbkm-registrations/create`)

**Allowed when:**
- ✅ Student is new (no applications)
- ✅ Student has no active applications

**Blocked when:**
- ❌ Student has rejected MBKM registration (ineligible)
- ❌ Student has active MBKM registration
- ❌ Student has active Skripsi registration

### Skripsi Registration (`/frontend/skripsi-registrations/create`)

**Allowed when:**
- ✅ Student is new (no applications)
- ✅ Student has no active applications
- ✅ Student was rejected from MBKM (can choose regular path)

**Blocked when:**
- ❌ Student has active Skripsi registration
- ❌ Student has approved MBKM registration

### MBKM Seminar (`/frontend/mbkm-seminars/create`)

**Allowed when:**
- ✅ MBKM registration is approved
- ✅ No active seminar application exists

**Blocked when:**
- ❌ No MBKM registration or not approved
- ❌ Seminar already submitted/approved

### Skripsi Seminar (`/frontend/skripsi-seminars/create`)

**Allowed when:**
- ✅ Skripsi registration is approved
- ✅ No active seminar application exists

**Blocked when:**
- ❌ No Skripsi registration or not approved
- ❌ Seminar already submitted/approved

### Skripsi Defense (`/frontend/skripsi-defenses/create`)

**Allowed when:**
- ✅ Seminar (MBKM or Skripsi) is approved
- ✅ Seminar result exists and is not "failed"
- ✅ No active defense application exists

**Blocked when:**
- ❌ No approved seminar
- ❌ Seminar result not yet input
- ❌ Seminar result is "failed" (must repeat seminar)
- ❌ Defense already submitted/approved

## Application Flow

### MBKM Path

```
1. MBKM Registration
   ↓ (admin approve + assign supervisor)
   ↓ (dosen approve supervision)
   ↓
2. MBKM Seminar
   ↓ (admin approve + assign reviewers)
   ↓
3. Seminar Schedule
   ↓ (admin approve)
   ↓
4. Seminar Result
   ↓ (admin approve, if passed/revision)
   ↓
5. Skripsi Defense
   ↓ (admin approve + assign examiners)
   ↓
6. Defense Schedule
   ↓ (admin approve)
   ↓
7. Defense Result
   ↓ (admin approve → grading ready)
   ↓
8. Scores Input
```

### Regular Skripsi Path

```
1. Skripsi Registration
   ↓ (admin approve + assign supervisor)
   ↓ (dosen approve supervision)
   ↓
2. Skripsi Seminar
   ↓ (admin approve + assign reviewers)
   ↓
3. Seminar Schedule
   ↓ (admin approve)
   ↓
4. Seminar Result
   ↓ (admin approve, if passed/revision)
   ↓
5. Skripsi Defense
   ↓ (admin approve + assign examiners)
   ↓
6. Defense Schedule
   ↓ (admin approve)
   ↓
7. Defense Result
   ↓ (admin approve → grading ready)
   ↓
8. Scores Input
```

## Error Messages

The system provides clear, contextual error messages:

| Scenario | Message |
|----------|---------|
| MBKM rejected student | "Anda tidak eligible untuk jalur MBKM. Silakan daftar melalui jalur Skripsi Reguler." |
| Active MBKM exists | "Anda sudah memiliki pendaftaran MBKM yang aktif. Tunggu proses persetujuan." |
| Active Skripsi exists | "Anda sudah memiliki pendaftaran Skripsi yang aktif." |
| No approved registration | "Anda harus menyelesaikan pendaftaran [MBKM/Skripsi] terlebih dahulu dan mendapat persetujuan." |
| Seminar result pending | "Hasil seminar proposal Anda belum diinput oleh admin." |
| Seminar failed | "Anda harus mengulang seminar proposal terlebih dahulu." |

## Usage in Views

In your Blade templates, you can now check form access:

```blade
{{-- Check if student can access MBKM registration --}}
@if(isset($allowedForms) && $allowedForms['mbkm_registration']['allowed'])
    <a href="{{ route('frontend.mbkm-registrations.create') }}" class="btn btn-primary">
        Daftar MBKM
    </a>
@else
    <button class="btn btn-secondary" disabled 
            title="{{ $allowedForms['mbkm_registration']['message'] ?? 'Tidak tersedia' }}">
        Daftar MBKM (Tidak Tersedia)
    </button>
    @if(isset($allowedForms['mbkm_registration']['message']))
        <small class="text-muted">{{ $allowedForms['mbkm_registration']['message'] }}</small>
    @endif
@endif
```

## Testing the Implementation

### Test Case 1: New Student
- ✅ Can access both MBKM and Skripsi registration
- ❌ Cannot access any seminar or defense forms

### Test Case 2: Student with Submitted MBKM Registration
- ❌ Cannot access MBKM registration (already submitted)
- ❌ Cannot access Skripsi registration (path chosen)
- ❌ Cannot access seminars (not approved yet)

### Test Case 3: Student with Approved MBKM Registration
- ❌ Cannot access registrations
- ✅ Can access MBKM seminar
- ❌ Cannot access Skripsi seminar

### Test Case 4: Student with Rejected MBKM
- ❌ Cannot access MBKM registration (ineligible)
- ✅ Can access Skripsi registration
- ❌ Cannot access seminars

### Test Case 5: Student with Approved Seminar
- ❌ Cannot access registrations or seminars
- ✅ Can access defense (if result is passed/revision)
- ❌ Cannot access defense (if result is failed)

## Benefits

1. **Data Integrity** - Prevents students from skipping steps or having multiple active applications
2. **Better UX** - Clear messaging about what students can/cannot do
3. **Centralized Logic** - All access rules in one service, easy to maintain
4. **Flexible** - Easy to add new rules or modify existing ones
5. **Secure** - Server-side validation prevents bypassing client-side restrictions

## Future Enhancements

Potential improvements:

1. Add caching to `FormAccessService` to reduce database queries
2. Add event listeners to automatically update form access when application status changes
3. Create middleware for route-level protection
4. Add admin interface to view/manage student access states
5. Add logging for access attempts (both allowed and denied)

