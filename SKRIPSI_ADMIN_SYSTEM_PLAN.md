# Skripsi Admin System - Implementation Plan

## Menu Structure

```
Admin Panel
└── Skripsi Management
    ├── Dashboard (Overview & Statistics)
    ├── Registrations (Pendaftaran Topik)
    ├── Seminars (Seminar Proposal)
    └── Defenses (Sidang Akhir)
```

## 1. Dashboard Features

### Statistics Cards
- Total Registrations (All stages)
- Pending Approvals
- Approved Applications
- Rejected Applications

### Charts/Graphs
- Students per stage (Registration → Seminar → Defense)
- Monthly submissions trend
- Approval rate

### Data Table (AJAX)
- Recent submissions
- Status overview
- Quick actions

## 2. Registration List Features

### Filters
- Status (Submitted, Approved, Rejected, Scheduled)
- Date range
- Student/Mahasiswa
- Dosen pembimbing

### Columns
- Application ID
- Student Name/NIM
- Title
- Theme
- Submission Date
- Status
- Actions

## 3. Detailed Show Page

### Sections

#### A. Application Information
- Application ID
- Application Type
- Stage
- Status
- Submission Date

#### B. Student Information
- NIM
- Name
- Faculty/Prodi
- Contact

#### C. Registration Details
- Thesis Title
- Abstract
- Theme/Keilmuan
- TPS Lecturer
- Preferred Supervisor

#### D. Documents (with Preview)
- KHS (All semesters) - Can preview PDF
- KRS (Latest semester) - Can preview PDF
- Download all option

#### E. Admin Actions
- **If Submitted:**
  - Approve (Assign supervisor)
  - Reject (With reason)
  - Request Revision (With notes)

- **If Approved:**
  - View approval details
  - Reassign supervisor
  - Move to next stage

- **If Rejected:**
  - View rejection reason
  - Allow resubmission

### Action Buttons

```
[Approve & Assign Supervisor] [Reject] [Request Revision]
```

## 4. Approval/Rejection Workflow

### Approve Flow:
1. Click "Approve" button
2. Modal opens to select supervisor
3. Admin selects supervisor from dropdown
4. Optional: Add notes
5. Confirm → Status changes to "Approved"
6. Email notification sent to student

### Reject Flow:
1. Click "Reject" button
2. Modal opens with rejection form
3. Admin enters rejection reason (required)
4. Admin can select rejection category
5. Confirm → Status changes to "Rejected"
6. Email notification sent to student with reason

### Request Revision Flow:
1. Click "Request Revision"
2. Modal opens with revision notes form
3. Admin enters what needs to be revised
4. Confirm → Status changes to "Revision Requested"
5. Student can edit and resubmit

## 5. Database Changes Needed

### Applications Table (existing)
- `status` field values:
  - submitted
  - approved
  - rejected
  - revision_requested
  - scheduled
  - done

### New Table: application_actions
```sql
- id
- application_id (foreign key)
- action_type (approved, rejected, revision_requested, etc.)
- action_by (admin user_id)
- reason/notes (text)
- created_at
- updated_at
```

### SkripsiRegistration Table
- Add: `assigned_supervisor_id` (foreign key to dosens)
- Add: `approval_date` (datetime)
- Add: `rejection_reason` (text, nullable)

## 6. Routes Structure

```php
// Admin Routes
Route::prefix('admin')->name('admin.')->group(function() {
    // Skripsi Dashboard
    Route::get('/skripsi/dashboard', 'SkripsiDashboardController@index')
        ->name('skripsi.dashboard');
    Route::get('/skripsi/dashboard/data', 'SkripsiDashboardController@getData')
        ->name('skripsi.dashboard.data');
    
    // Registrations
    Route::get('/skripsi/registrations', 'SkripsiRegistrationController@index')
        ->name('skripsi-registrations.index');
    Route::get('/skripsi/registrations/{id}', 'SkripsiRegistrationController@show')
        ->name('skripsi-registrations.show');
    Route::post('/skripsi/registrations/{id}/approve', 'SkripsiRegistrationController@approve')
        ->name('skripsi-registrations.approve');
    Route::post('/skripsi/registrations/{id}/reject', 'SkripsiRegistrationController@reject')
        ->name('skripsi-registrations.reject');
    Route::post('/skripsi/registrations/{id}/request-revision', 'SkripsiRegistrationController@requestRevision')
        ->name('skripsi-registrations.request-revision');
});
```

## 7. Implementation Order

1. ✅ Create migration for application_actions table
2. ✅ Create ApplicationAction model
3. ✅ Create SkripsiDashboardController
4. ✅ Create dashboard view with statistics
5. ✅ Enhance SkripsiRegistrationController with action methods
6. ✅ Create enhanced show view with all sections
7. ✅ Create approval/rejection modals
8. ✅ Implement AJAX for actions
9. ✅ Add routes
10. ✅ Update navigation menu
11. ✅ Add permissions/gates
12. ✅ Test all workflows

## 8. UI/UX Considerations

### Color Coding
- **Submitted**: Blue (info)
- **Approved**: Green (success)
- **Rejected**: Red (danger)
- **Revision Requested**: Orange (warning)
- **Scheduled**: Purple
- **Done**: Gray

### Icons
- Dashboard: fas fa-tachometer-alt
- Registrations: fas fa-file-alt
- Approve: fas fa-check-circle
- Reject: fas fa-times-circle
- Revision: fas fa-edit
- Preview: fas fa-eye

### Responsive Design
- Mobile-friendly tables
- Collapsible sections on small screens
- Touch-friendly action buttons

