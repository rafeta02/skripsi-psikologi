# Thesis Management System - Master Implementation Plan

Based on `PROSES_ALUR_SKRIPSI.md` analysis, this document outlines the complete implementation plan for all 3 actors.

## 🎯 System Overview

### 3 Actors:
1. **Mahasiswa (Student)** - Submit forms, upload documents, schedule events
2. **Dosen (Lecturer)** - Review proposals, supervise, grade defenses
3. **Administrator** - Verify submissions, assign lecturers, control status

### 2 Thesis Flows:
1. **Skripsi Reguler** - Individual proposal review (no formal seminar)
2. **Skripsi MBKM** - Formal MBKM seminar with scheduled presentation

---

## ✅ COMPLETED (Already Working)

### Mahasiswa:
- [x] Layout with navigation
- [x] Dashboard with statistics
- [x] Path Selection (Skripsi vs MBKM)
- [x] Skripsi Registration (create form)
- [x] Application list/show pages
- [x] Skripsi Seminar (index, create, show, edit)
- [x] Profile page

### Dosen:
- [x] Layout with navigation
- [x] Dashboard with statistics
- [x] Mahasiswa Bimbingan list
- [x] Task Assignments (with respond modal)
- [x] Scores list
- [x] Profile page

### Shared:
- [x] Design system CSS
- [x] Form components CSS
- [x] Timeline component (just created)

---

## ❌ PENDING IMPLEMENTATION

### Priority 1: Critical Mahasiswa Forms (Core Flow)

#### 1. MBKM Seminar Views
**Path:** `resources/views/frontend/mbkmSeminars/`
- `index.blade.php` - List MBKM seminar registrations
- `create.blade.php` - Register for MBKM seminar
- `show.blade.php` - View seminar details
- `edit.blade.php` - Edit seminar registration

**Controller:** `app/Http/Controllers/Frontend/MbkmSeminarController.php` (exists)
**Routes:** Already in `routes/web.php`
**Validation:** Update `StoreMbkmSeminarRequest.php` and `UpdateMbkmSeminarRequest.php`

#### 2. Application Schedule Views
**Path:** `resources/views/frontend/applicationSchedules/`
- `index.blade.php` - List all schedules
- `create.blade.php` - Create new schedule (seminar/defense)
- `show.blade.php` - View schedule details
- `edit.blade.php` - Edit schedule

**Purpose:** Schedule both MBKM seminars AND thesis defenses
**Controller:** `app/Http/Controllers/Frontend/ApplicationScheduleController.php` (needs creation)
**Routes:** Need to add to `routes/web.php`

#### 3. Application Result Seminar Views
**Path:** `resources/views/frontend/applicationResultSeminars/`
- `create.blade.php` - Report proposal review results
- `show.blade.php` - View reported results

**Purpose:** Mahasiswa reports results after proposal review (Skripsi Reguler only)
**Controller:** `app/Http/Controllers/Frontend/ApplicationResultSeminarController.php` (needs creation)

#### 4. Skripsi Defense Views
**Path:** `resources/views/frontend/skripsiDefenses/`
- `index.blade.php` - List defense registrations
- `create.blade.php` - Register for thesis defense
- `show.blade.php` - View defense details
- `edit.blade.php` - Edit defense registration

**Purpose:** Register for final thesis defense (both Skripsi & MBKM)
**Controller:** `app/Http/Controllers/Frontend/SkripsiDefenseController.php` (needs creation)

#### 5. Application Result Defense Views
**Path:** `resources/views/frontend/applicationResultDefenses/`
- `create.blade.php` - Upload defense results
- `show.blade.php` - View defense results

**Purpose:** Upload final documents after defense
**Controller:** `app/Http/Controllers/Frontend/ApplicationResultDefenseController.php` (needs creation)

#### 6. Application Report Views
**Path:** `resources/views/frontend/applicationReports/`
- `index.blade.php` - List all reports
- `create.blade.php` - Create issue report
- `show.blade.php` - View report details

**Purpose:** Report any issues during thesis process (optional, anytime)
**Controller:** `app/Http/Controllers/Frontend/ApplicationReportController.php` (needs creation)

---

### Priority 2: Dosen Features

#### 7. Proposal Review Page
**Path:** `resources/views/dosen/proposals/review.blade.php`
**Purpose:** Dosen can review proposals and provide feedback
**Features:**
- View proposal documents
- Provide written feedback
- Approve/Request revision/Reject
**Access:** From Task Assignments when role = 'reviewer'

#### 8. Defense Scoring Page
**Path:** `resources/views/dosen/defenses/scoring.blade.php`
**Model:** `ApplicationScore`
**Purpose:** Grade student's defense performance
**Features:**
- Input scores (multiple criteria)
- Add notes/feedback
- Submit final grade
**Access:** After defense completion

#### 9. Mahasiswa Timeline View
**Enhancement:** Add timeline to Dosen's mahasiswa bimbingan page
**Shows:** Current progress of each supervised student

---

### Priority 3: Admin System (Complete New Section)

#### 10. Admin Layout & Dashboard
**Path:** `resources/views/layouts/admin.blade.php`
**Dashboard:** `resources/views/admin/dashboard.blade.php`
**Features:**
- Statistics: Total registrations, pending verifications, active defenses
- Quick actions panel
- Recent activity feed
- Alerts for pending tasks

#### 11. Registration Verification Pages
**For:** `SkripsiRegistration` and `MbkmRegistration`
**Path:** `resources/views/admin/registrations/`
- `skripsi-index.blade.php` - List pending Skripsi registrations
- `skripsi-show.blade.php` - Review & verify Skripsi registration
- `mbkm-index.blade.php` - List pending MBKM registrations
- `mbkm-show.blade.php` - Review & verify MBKM registration

**Actions:**
- Approve
- Request Revision
- Reject (for MBKM: mark as ineligible)

#### 12. Dosen Assignment Pages
**Model:** `ApplicationAssignment`
**Path:** `resources/views/admin/assignments/`
- `create.blade.php` - Assign pembimbing/reviewer/penguji
- `index.blade.php` - View all assignments
- `manage.blade.php` - Manage assignments per application

**Features:**
- Select dosen by keilmuan/research group
- Assign multiple reviewers/examiners
- Track assignment status

#### 13. Seminar Verification Pages
**For:** `SkripsiSeminar` and `MbkmSeminar`
**Path:** `resources/views/admin/seminars/`
- `skripsi-index.blade.php` - List pending Skripsi seminar registrations
- `skripsi-show.blade.php` - Verify Skripsi seminar registration
- `mbkm-index.blade.php` - List pending MBKM seminar registrations
- `mbkm-show.blade.php` - Verify MBKM seminar registration

#### 14. Schedule Verification Pages
**For:** `ApplicationSchedule`
**Path:** `resources/views/admin/schedules/`
- `index.blade.php` - List all schedule requests
- `show.blade.php` - Review & verify schedule
- `calendar.blade.php` - Calendar view of all schedules

**Features:**
- Check room availability
- Verify dosen availability
- Approve/reject schedule

#### 15. Defense Verification Pages
**For:** `SkripsiDefense`
**Path:** `resources/views/admin/defenses/`
- `index.blade.php` - List pending defense registrations
- `show.blade.php` - Verify defense registration

#### 16. Monitoring Dashboard
**Path:** `resources/views/admin/monitoring/`
- `overview.blade.php` - All students overview
- `timeline.blade.php` - Timeline view for specific student
- `reports.blade.php` - Generate reports

**Features:**
- Filter by stage, status, prodi
- Export to Excel
- Timeline for each student

---

## 📋 Database Models Status

### ✅ Models Exist (Need Controllers/Views):
- `Application`
- `SkripsiRegistration`
- `MbkmRegistration`
- `SkripsiSeminar`
- `MbkmSeminar`
- `ApplicationSchedule`
- `ApplicationResultSeminar`
- `ApplicationResultDefense`
- `SkripsiDefense`
- `ApplicationAssignment`
- `ApplicationScore`
- `ApplicationReport`

### Services:
- ✅ `FormAccessService` - Already exists for form access control

---

## 🛣️ Route Structure Needed

```php
// Frontend Routes (Mahasiswa)
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    // Already exists: skripsi-seminars
    Route::resource('mbkm-seminars', 'MbkmSeminarController');
    Route::resource('application-schedules', 'ApplicationScheduleController');
    Route::resource('application-result-seminars', 'ApplicationResultSeminarController');
    Route::resource('skripsi-defenses', 'SkripsiDefenseController');
    Route::resource('application-result-defenses', 'ApplicationResultDefenseController');
    Route::resource('application-reports', 'ApplicationReportController');
});

// Dosen Routes
Route::group(['prefix' => 'dosen', 'as' => 'dosen.', 'middleware' => ['auth', 'role:dosen']], function () {
    // Already exists: dashboard, mahasiswa-bimbingan, task-assignments, scores
    Route::get('proposals/{assignment}/review', 'ProposalReviewController@show')->name('proposals.review');
    Route::post('proposals/{assignment}/review', 'ProposalReviewController@store')->name('proposals.review.store');
    Route::get('defenses/{defense}/scoring', 'DefenseScoringController@show')->name('defenses.scoring');
    Route::post('defenses/{defense}/scoring', 'DefenseScoringController@store')->name('defenses.scoring.store');
});

// Admin Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
    
    // Registrations
    Route::get('registrations/skripsi', 'Admin\RegistrationController@skripsiIndex')->name('registrations.skripsi.index');
    Route::post('registrations/skripsi/{id}/verify', 'Admin\RegistrationController@verifySkripsi')->name('registrations.skripsi.verify');
    Route::get('registrations/mbkm', 'Admin\RegistrationController@mbkmIndex')->name('registrations.mbkm.index');
    Route::post('registrations/mbkm/{id}/verify', 'Admin\RegistrationController@verifyMbkm')->name('registrations.mbkm.verify');
    
    // Assignments
    Route::resource('assignments', 'Admin\AssignmentController');
    
    // Seminars
    Route::get('seminars/skripsi', 'Admin\SeminarController@skripsiIndex')->name('seminars.skripsi.index');
    Route::post('seminars/skripsi/{id}/verify', 'Admin\SeminarController@verifySkripsi')->name('seminars.skripsi.verify');
    Route::get('seminars/mbkm', 'Admin\SeminarController@mbkmIndex')->name('seminars.mbkm.index');
    Route::post('seminars/mbkm/{id}/verify', 'Admin\SeminarController@verifyMbkm')->name('seminars.mbkm.verify');
    
    // Schedules
    Route::resource('schedules', 'Admin\ScheduleController');
    Route::post('schedules/{id}/verify', 'Admin\ScheduleController@verify')->name('schedules.verify');
    
    // Defenses
    Route::get('defenses', 'Admin\DefenseController@index')->name('defenses.index');
    Route::post('defenses/{id}/verify', 'Admin\DefenseController@verify')->name('defenses.verify');
    
    // Monitoring
    Route::get('monitoring', 'Admin\MonitoringController@index')->name('monitoring.index');
    Route::get('monitoring/{mahasiswa}', 'Admin\MonitoringController@show')->name('monitoring.show');
});
```

---

## 🎨 UI/UX Consistency

All views must follow the established design system:

### CSS Classes:
- `card-modern`, `card-modern-body`, `card-modern-header`
- `btn-modern`, `btn-modern-primary`, `btn-modern-outline`
- `badge-modern`, `badge-modern-{color}`
- `form-label-modern`, `form-control-modern`
- `table-modern`, `table-modern-striped`

### Color Scheme:
- **Mahasiswa:** Blue-purple gradient (`--primary-500`, `--secondary-500`)
- **Dosen:** Deep purple (`--dosen-primary: #22004C`)
- **Admin:** Orange/red tones (to be defined)

### Components:
- Gradient hero headers
- Status badges
- Empty states with icons
- File upload with custom labels
- Timeline component (now available)
- SweetAlert2 for notifications

---

## 📦 Implementation Order

### Phase 1: Critical Forms (Mahasiswa)
1. ✅ Timeline Component
2. MBKM Seminar views
3. Application Schedule views
4. Skripsi Defense views
5. Application Result Seminar views
6. Application Result Defense views
7. Application Report views

### Phase 2: Dosen Features
8. Proposal Review page
9. Defense Scoring page
10. Timeline integration

### Phase 3: Admin System
11. Admin layout & dashboard
12. Registration verification
13. Dosen assignment
14. Seminar verification
15. Schedule verification
16. Defense verification
17. Monitoring system

---

## 🔄 Current Status: Phase 1 - Task 1 Complete

✅ **Timeline Component created**
- Vertical and horizontal layouts
- Shows progress through stages
- Different stages for Skripsi vs MBKM
- Status indicators
- Reusable across all actors

**Next:** Start implementing MBKM Seminar views (Task 2)

---

## 📝 Notes

- All file uploads should use standard Laravel file validation (not Dropzone)
- Maximum file size: 10MB for PDF documents
- All forms need proper validation requests
- Use `FormAccessService` to check if student can access specific forms
- Timeline component should be integrated in:
  - Mahasiswa dashboard
  - Application show pages
  - Dosen's mahasiswa bimbingan page
  - Admin's monitoring pages

---

## ⚠️ Important Considerations

1. **Permission System**: Ensure Gates are properly defined for all actions
2. **Form Access Control**: Use `FormAccessService` consistently
3. **Status Flow**: Maintain proper status transitions (submitted → approved → scheduled → done)
4. **Notification System**: Consider adding email/system notifications for status changes
5. **Audit Trail**: Log important actions (verifications, assignments, status changes)
6. **File Storage**: Organize uploaded files by type and student
7. **Validation**: Comprehensive validation for all forms
8. **Error Handling**: User-friendly error messages
9. **Mobile Responsiveness**: All views must work on mobile devices
10. **Loading States**: Show loading indicators for long operations

---

**Document Created:** 2026-05-09
**Last Updated:** 2026-05-09
**Status:** In Progress - Phase 1, Task 1 Complete
