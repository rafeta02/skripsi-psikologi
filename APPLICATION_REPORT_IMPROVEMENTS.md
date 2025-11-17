# Application Report System Improvements

## Overview
This document outlines the improvements made to the Application Report system to enhance security, usability, and file management.

## Changes Made

### 1. Model Updates (`app/Models/ApplicationReport.php`)
- **Added FileNamingTrait**: Integrated the `FileNamingTrait` to ensure consistent file naming for uploaded documents
- **File Naming Format**: Documents are now named as `{application_id}_report_document_{uniqueid}.{extension}`

### 2. Controller Updates

#### Admin Controller (`app/Http/Controllers/Admin/ApplicationReportController.php`)
- **create() method**: 
  - Gets the current mahasiswa's **active application** (status: submitted, approved, or scheduled)
  - Returns error if no active application exists
  - Passes `activeApplication` to view instead of applications list
  
- **store() method**:
  - Automatically sets status to 'submitted' when a new report is created
  - Uses FileNamingTrait for proper file naming
  
- **edit() method**:
  - Removed applications dropdown - application is fixed once report is created
  
- **update() method**:
  - Maintains custom file naming when updating documents
  - Uses FileNamingTrait for new document uploads

#### Frontend Controller (`app/Http/Controllers/Frontend/ApplicationReportController.php`)
- **index() method**:
  - Filters reports to show only those belonging to the current mahasiswa's applications
  - Returns empty collection if mahasiswa profile doesn't exist
  
- **create() method**:
  - Gets the current mahasiswa's **active application** automatically
  - Returns error if no active application exists
  - Passes `activeApplication` to view
  
- **store() method**:
  - Automatically sets status to 'submitted'
  - Uses FileNamingTrait for document naming
  
- **edit() method**:
  - Application field is read-only in edit mode
  - No dropdown selection needed
  
- **update() method**:
  - Uses FileNamingTrait for document naming during updates

### 3. View Updates

#### Admin Views

**create.blade.php** (`resources/views/admin/applicationReports/create.blade.php`)
- **Application Field**: 
  - Changed from dropdown to **hidden input** with read-only display
  - Automatically uses mahasiswa's active application
  - Shows application type as read-only text
  - Helper text: "Laporan ini terkait dengan aplikasi aktif Anda"
- **Removed**: Status field (auto-set to 'submitted')
- **Removed**: Note field (only admin can add notes during review)
- **Retained**: Period, Report text, and Document upload fields

**edit.blade.php** (`resources/views/admin/applicationReports/edit.blade.php`)
- **Application Field**:
  - Hidden input preserves application_id
  - Read-only display showing application type
  - Helper text: "Aplikasi tidak dapat diubah setelah laporan dibuat"
- **Status Field**: 
  - Visible and editable for Admin users
  - Read-only display for non-admin users with message "Status hanya dapat diubah oleh admin"
- **Note Field**:
  - Editable for Admin users
  - Read-only for non-admin users (shown only if note exists) with label "Catatan dari admin"

#### Frontend Views

**create.blade.php** (`resources/views/frontend/applicationReports/create.blade.php`)
- **Application Field**:
  - **Hidden input** with value from active application
  - Read-only display showing application type
  - Styled with light background (`#f8f9fa`)
  - Helper text: "Laporan ini akan terkait dengan aplikasi aktif Anda"
- **Removed**: Status field (auto-set to 'submitted' by backend)
- **Removed**: Note field (admin-only field)
- **Enhanced UI**: Modern, user-friendly interface with:
  - Clear form header explaining the purpose
  - Info box with important information
  - Better visual hierarchy and spacing
  - Improved dropzone styling

**edit.blade.php** (`resources/views/frontend/applicationReports/edit.blade.php`)
- **Application Field**:
  - Hidden input preserves application_id
  - Read-only display with light background
  - Helper text: "Aplikasi tidak dapat diubah setelah laporan dibuat"
- **Status Field**: 
  - Editable for Admin users only
  - Read-only display for Mahasiswa with helper text
- **Note Field**:
  - Editable for Admin users
  - Read-only for Mahasiswa (displayed only if exists)
  - Shows "Catatan dari admin" label for mahasiswa

## Workflow

### Mahasiswa Workflow
1. **Create Report**:
   - System automatically uses their **active application** (no selection needed)
   - If no active application exists, redirected with error message
   - Fill in report details and upload supporting documents
   - Status is automatically set to 'submitted'
   - Cannot set or modify status/notes

2. **View/Edit Report**:
   - Can only view their own reports
   - Application field is read-only (cannot be changed)
   - Status is displayed as read-only
   - Admin notes are displayed as read-only (if any)
   - Can update report content but not application or status

### Admin Workflow
1. **Review Reports**:
   - View all submitted reports in the system
   - Can see status and all details

2. **Process Report**:
   - Can change status from 'submitted' to 'reviewed'
   - Can add notes for mahasiswa
   - Full control over all fields

## Security Improvements
1. **Automatic Application Selection**: System automatically uses mahasiswa's active application
   - Prevents selection of other students' applications
   - No dropdown = no opportunity for manipulation
   - Application is locked once report is created
2. **Active Application Validation**: 
   - Reports can only be created if mahasiswa has an active application
   - Status must be: submitted, approved, or scheduled
3. **Status Control**: Only admins can change report status
4. **Note Control**: Only admins can add/edit notes
5. **Auto-Status**: New reports automatically get 'submitted' status, preventing manipulation

## File Naming Convention
All uploaded documents follow the FileNamingTrait pattern:
- Format: `{application_id}_report_document_{uniqueid}.{extension}`
- Example: `123_report_document_abc123def.pdf`
- Benefits:
  - Easy identification of which application the document belongs to
  - Prevents file name conflicts
  - Maintains organization in storage

## Status Flow
1. **Submitted**: Initial status when mahasiswa creates a report
2. **Reviewed**: Status set by admin after reviewing and responding to the report

## Benefits
1. **Enhanced Security**: 
   - Mahasiswa can only access their own data
   - Automatic application assignment prevents manipulation
   - Application cannot be changed after report creation
2. **Simplified User Experience**:
   - No need to select application - system does it automatically
   - Cleaner, simpler forms
   - Less room for user error
3. **Clear Workflow**: Defined roles for mahasiswa and admin
4. **Organized Files**: Consistent file naming makes document management easier
5. **Better Context**: Reports always tied to active application
6. **Audit Trail**: Clear status progression and admin notes for tracking

## Active Application Logic
- **Active Application** is determined by:
  - Status must be: `submitted`, `approved`, or `scheduled`
  - Most recent application (ordered by `created_at DESC`)
  - Belongs to the current mahasiswa
- If no active application exists:
  - User is redirected back with error message
  - Message: "Tidak ada aplikasi aktif. Silakan buat aplikasi terlebih dahulu."

