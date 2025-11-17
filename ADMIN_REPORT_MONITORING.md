# Admin Report Monitoring System

## Overview
Halaman monitoring admin untuk Application Reports yang memungkinkan admin untuk memantau semua laporan kendala dari mahasiswa dan mengubah status laporan menjadi "Reviewed" setelah ditinjau.

## Features Implemented

### 1. Enhanced Index Page
**File**: `resources/views/admin/applicationReports/index.blade.php`

#### Kolom Tabel yang Ditampilkan:
1. **Checkbox** - Untuk bulk actions
2. **Mahasiswa** - Nama mahasiswa yang melaporkan
3. **NIM** - NIM mahasiswa
4. **Aplikasi** - Tipe aplikasi skripsi
5. **Periode** - Periode laporan
6. **Status** - Badge status (Submitted/Reviewed)
7. **Catatan** - Preview catatan (50 karakter)
8. **Review Action** - Tombol/Badge untuk mark as reviewed
9. **Actions** - View, Edit, Delete

#### Visual Improvements:
- ✅ Info box dengan penjelasan fungsi halaman
- ✅ Status badges berwarna:
  - **Warning (Kuning)**: Submitted
  - **Success (Hijau)**: Reviewed
- ✅ Action button **"Mark as Reviewed"** untuk laporan yang submitted
- ✅ Badge checkmark untuk laporan yang sudah reviewed
- ✅ Default ordering: Status ascending (Submitted muncul pertama)
- ✅ Page length: 25 items per page

### 2. Enhanced Show Page
**File**: `resources/views/admin/applicationReports/show.blade.php`

#### Informasi yang Ditampilkan:

**A. Header Card:**
- Judul dengan icon
- Action button "Mark as Reviewed" (jika status = submitted)
- Badge "Reviewed" (jika sudah reviewed)

**B. Informasi Mahasiswa (Alert Box):**
- Nama mahasiswa
- NIM
- Email

**C. Detail Laporan:**
- Aplikasi (dengan badge)
- Periode laporan
- Status (dengan badge)
- Deskripsi kendala (formatted HTML)
- Dokumen pendukung (download buttons)
- Catatan admin
- Tanggal dibuat

**D. Footer Actions:**
- Back to list button
- Edit button (dengan permission check)

### 3. Controller Enhancements
**File**: `app/Http/Controllers/Admin/ApplicationReportController.php`

#### New Method: `markAsReviewed()`
```php
public function markAsReviewed(ApplicationReport $applicationReport)
{
    abort_if(Gate::denies('application_report_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
    $applicationReport->update(['status' => 'reviewed']);
    
    return redirect()->route('admin.application-reports.index')
        ->with('message', 'Laporan telah ditandai sebagai Reviewed');
}
```

**Features:**
- ✅ Permission check (`application_report_edit`)
- ✅ Update status to 'reviewed'
- ✅ Redirect to index with success message

#### Enhanced `index()` Method:
**Added Columns:**
- `mahasiswa_name` - Dari relasi application.mahasiswa.nama
- `mahasiswa_nim` - Dari relasi application.mahasiswa.nim
- `status` - Dengan HTML badge (rawColumn)
- `review_action` - Button/Badge dinamis (rawColumn)

**Features:**
- ✅ Eager loading: `with(['application.mahasiswa'])`
- ✅ HTML badges untuk status
- ✅ Inline form untuk mark as reviewed
- ✅ Confirmation dialog sebelum mark as reviewed
- ✅ Truncate note (50 char preview)

### 4. Route Configuration
**File**: `routes/web.php`

**New Route Added:**
```php
Route::post('application-reports/{application_report}/mark-reviewed', 
    'ApplicationReportController@markAsReviewed')
    ->name('application-reports.mark-reviewed');
```

**Position**: Before resource route (line 141)

## User Flow

### Admin Monitoring Workflow

```
1. Admin membuka halaman Application Reports
   ↓
2. Melihat daftar semua laporan dengan info mahasiswa
   ↓
3. Laporan dengan status "Submitted" menampilkan tombol "Mark as Reviewed"
   ↓
4. Admin klik tombol atau buka detail laporan
   ↓
5. Review isi laporan dan dokumen
   ↓
6. Klik "Mark as Reviewed"
   ↓
7. Confirm dialog muncul
   ↓
8. Status berubah menjadi "Reviewed"
   ↓
9. Redirect ke index dengan pesan sukses
```

### Status States

**Submitted (Warning Badge):**
- Warna: Kuning
- Action Available: ✅ Mark as Reviewed button
- Meaning: Laporan baru, belum ditinjau

**Reviewed (Success Badge):**
- Warna: Hijau
- Action: ✅ Check icon badge (no button)
- Meaning: Laporan sudah ditinjau admin

## UI Components

### Index Page

#### Info Alert:
```html
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> 
    <strong>Informasi:</strong> Halaman ini menampilkan semua laporan kendala...
</div>
```

#### Status Badges:
```html
<!-- Submitted -->
<span class="badge badge-warning">Submitted</span>

<!-- Reviewed -->
<span class="badge badge-success">Reviewed</span>
```

#### Review Action Button (Submitted):
```html
<form action="{{ route('admin.application-reports.mark-reviewed', $row->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-sm btn-primary" 
            onclick="return confirm('Tandai laporan ini sebagai Reviewed?')">
        <i class="fas fa-check"></i> Mark as Reviewed
    </button>
</form>
```

#### Review Action Badge (Reviewed):
```html
<span class="badge badge-success">
    <i class="fas fa-check-circle"></i> Reviewed
</span>
```

### Show Page

#### Header with Action:
```html
<div class="d-flex justify-content-between align-items-center">
    <h3><i class="fas fa-flag"></i> Detail Laporan Kendala</h3>
    @if($applicationReport->status == 'submitted')
        <form>...</form> <!-- Mark as Reviewed button -->
    @else
        <span class="badge badge-success badge-lg">
            <i class="fas fa-check-circle"></i> Reviewed
        </span>
    @endif
</div>
```

#### Mahasiswa Info Box:
```html
<div class="alert alert-info">
    <h5><i class="fas fa-user"></i> Informasi Mahasiswa</h5>
    <table class="table table-sm table-borderless mb-0">
        <!-- Mahasiswa details -->
    </table>
</div>
```

## DataTables Configuration

```javascript
columns: [
  { data: 'placeholder', name: 'placeholder' },
  { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
  { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
  { data: 'application_type', name: 'application.type' },
  { data: 'period', name: 'period' },
  { data: 'status', name: 'status' },
  { data: 'note', name: 'note' },
  { data: 'review_action', name: 'review_action', orderable: false, searchable: false },
  { data: 'actions', name: '{{ trans('global.actions') }}' }
],
order: [[ 5, 'asc' ]], // Order by status (submitted first)
pageLength: 25,
```

**Features:**
- Searchable by: Mahasiswa name, NIM, Application type, Period, Note
- Sortable by: All columns except Review Action
- Default sort: Status ascending (Submitted appear first)
- Pagination: 25 items per page

## Permissions Required

### View Reports:
- `application_report_access`

### Mark as Reviewed:
- `application_report_edit`

### Edit Report:
- `application_report_edit`

### Delete Report:
- `application_report_delete`

## Database Relations

```
ApplicationReport
  ↓
  belongsTo: Application
    ↓
    belongsTo: Mahasiswa
```

**Eager Loading:**
```php
ApplicationReport::with(['application.mahasiswa'])
```

## Success Messages

After marking as reviewed:
```
'message' => 'Laporan telah ditandai sebagai Reviewed'
```

Display in layout:
```blade
@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
```

## Security Features

1. **Permission Checks:**
   - Gate check before marking as reviewed
   - Permission-based button visibility

2. **Confirmation Dialog:**
   - JavaScript confirm before status change
   - Prevents accidental clicks

3. **CSRF Protection:**
   - All forms include `@csrf` token

4. **Route Model Binding:**
   - Type-hinted `ApplicationReport` in controller

## Files Modified

### Controllers:
1. ✅ `app/Http/Controllers/Admin/ApplicationReportController.php`
   - Added `markAsReviewed()` method
   - Enhanced `index()` with mahasiswa data

### Views:
2. ✅ `resources/views/admin/applicationReports/index.blade.php`
   - Added mahasiswa columns
   - Added review action column
   - Enhanced with info box
   - Updated DataTables config

3. ✅ `resources/views/admin/applicationReports/show.blade.php`
   - Added mahasiswa info box
   - Added mark as reviewed button
   - Enhanced layout and styling
   - Better document display

### Routes:
4. ✅ `routes/web.php`
   - Added `mark-reviewed` route

## Testing Checklist

### Index Page:
- [ ] Can view all reports
- [ ] Mahasiswa name and NIM displayed correctly
- [ ] Status badges show correct colors
- [ ] "Mark as Reviewed" button appears for submitted reports
- [ ] Check icon appears for reviewed reports
- [ ] Can click "Mark as Reviewed" button
- [ ] Confirmation dialog appears
- [ ] Status updates successfully
- [ ] DataTables sorting works
- [ ] DataTables search works

### Show Page:
- [ ] Mahasiswa info displays correctly
- [ ] All report details visible
- [ ] Documents can be downloaded
- [ ] "Mark as Reviewed" button works (if submitted)
- [ ] Reviewed badge shows (if reviewed)
- [ ] Back button returns to index
- [ ] Edit button visible (with permission)

### Permissions:
- [ ] Only users with `application_report_access` can view
- [ ] Only users with `application_report_edit` can mark as reviewed
- [ ] Proper 403 errors for unauthorized access

## Benefits

### For Admin:
1. **Better Visibility**
   - See who reported (Mahasiswa name & NIM)
   - Quick status overview (badges)
   - Easy filtering and search

2. **Efficient Workflow**
   - One-click mark as reviewed
   - Clear visual distinction (submitted vs reviewed)
   - Bulk actions available

3. **Complete Information**
   - Mahasiswa contact info
   - Full report content
   - All documents accessible
   - Timestamp tracking

### For System:
1. **Better Tracking**
   - Clear audit trail
   - Status progression
   - Admin response time

2. **Improved Management**
   - Easy prioritization (submitted first)
   - Quick response capability
   - Better student support

## Future Enhancements

### Potential Additions:
1. **Email Notifications**
   - Notify student when marked as reviewed
   - Notify admin on new submissions

2. **Analytics Dashboard**
   - Total reports per period
   - Average response time
   - Common issues tracking

3. **Bulk Mark as Reviewed**
   - Select multiple reports
   - Mark all as reviewed at once

4. **Response Comments**
   - Admin can add response directly
   - Thread-style communication

5. **Status History**
   - Track who marked as reviewed
   - When status changed
   - Audit log integration

## Summary

Halaman monitoring admin untuk Application Reports sekarang menyediakan:
- ✅ View komprehensif dengan data mahasiswa
- ✅ Quick action untuk mark as reviewed
- ✅ Visual badges untuk status
- ✅ Detail page yang informatif
- ✅ Permission-based access control
- ✅ Efficient workflow untuk admin

Admin dapat dengan mudah memantau dan merespons laporan kendala mahasiswa dengan antarmuka yang user-friendly dan informatif.

