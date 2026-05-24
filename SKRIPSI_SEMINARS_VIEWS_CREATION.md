# Skripsi Seminars Views Creation

## Problem
Error: `View [frontend.skripsiSeminars.index] not found.`

The `SkripsiSeminarController` in `app/Http/Controllers/Frontend/` was trying to load views that didn't exist.

## Solution
Created complete CRUD views for Skripsi Seminars in the `resources/views/frontend/skripsiSeminars/` directory.

## Files Created

### 1. `resources/views/frontend/skripsiSeminars/index.blade.php`
**Purpose:** List all seminar registrations for the current student

**Features:**
- Modern card-based list layout
- Status badges (submitted, approved, scheduled, revision, rejected, done)
- Document links (proposal, approval, plagiarism check)
- Empty state with CTA button
- Action buttons (View Detail, Edit)
- Permission-based button visibility

**Key Elements:**
```blade
<!-- Status Badges -->
@if($seminar->application->status == 'submitted')
    <span class="badge-modern badge-modern-warning">Menunggu Review</span>
@elseif($seminar->application->status == 'approved')
    <span class="badge-modern badge-modern-success">Disetujui</span>
@endif

<!-- Documents -->
@if($seminar->proposal_document)
    <a href="{{ $seminar->proposal_document->getUrl() }}" target="_blank">
        <i class="fas fa-file-pdf"></i> Proposal
    </a>
@endif
```

### 2. `resources/views/frontend/skripsiSeminars/create.blade.php`
**Purpose:** Create new seminar registration

**Features:**
- Form with validation support
- File upload for 3 documents:
  - Proposal Document (required)
  - Approval Document (optional)
  - Plagiarism Check Document (optional)
- Auto-update file input labels with JavaScript
- Modern form styling
- Hidden `application_id` field for linking

**Key Fields:**
```blade
- title (required) - Judul Proposal
- description - Deskripsi/Abstrak
- proposal_document (PDF, required)
- approval_document (PDF, optional)
- plagiarism_document (PDF, optional)
- notes - Catatan tambahan
```

**JavaScript Enhancement:**
```javascript
// Update file input label when file selected
$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
});
```

### 3. `resources/views/frontend/skripsiSeminars/show.blade.php`
**Purpose:** Display detailed seminar registration information

**Features:**
- Two-column layout (main content + sidebar)
- Complete seminar information display
- Document cards with download links
- Status alert box with contextual styling
- Application information panel
- Edit button (conditional based on status and permissions)

**Layout Structure:**
```
┌─────────────────────────────────────────┐
│           Page Header                    │
└─────────────────────────────────────────┘
┌──────────────────┬────────────────────┐
│  Main Content    │    Sidebar         │
│  - Title         │    - Status Alert  │
│  - Description   │    - App Info      │
│  - Documents     │                    │
└──────────────────┴────────────────────┘
```

**Status Alerts:**
- Submitted: Yellow alert with clock icon
- Approved: Green alert with check icon
- Scheduled: Blue alert with calendar icon
- Revision: Yellow alert with edit icon
- Rejected: Red alert with times icon
- Done: Gray alert with flag icon

### 4. `resources/views/frontend/skripsiSeminars/edit.blade.php`
**Purpose:** Edit existing seminar registration

**Features:**
- Pre-filled form with existing data
- Current document preview with "View" links
- File replacement capability
- Same validation as create form
- "Ganti file..." vs "Pilih file..." labels based on existing documents

**Key Difference from Create:**
```blade
<!-- Shows current document -->
@if($skripsiSeminar->proposal_document)
    <div class="mb-2">
        <a href="{{ $skripsiSeminar->proposal_document->getUrl() }}" target="_blank">
            <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
        </a>
    </div>
@endif

<!-- Then file input for replacement -->
<input type="file" name="proposal_document" ...>
```

## Design System Integration

All views use the established design system:

### CSS Classes Used:
- `card-modern` - Modern card component
- `card-modern-body` - Card body with proper padding
- `badge-modern badge-modern-{color}` - Status badges
- `btn-modern btn-modern-{variant}` - Modern buttons
- `form-label-modern` - Modern form labels
- `form-control-modern` - Modern form inputs

### Color Scheme:
- Primary: `var(--primary-500)` - Purple gradient
- Secondary: `var(--secondary-500)` - Lighter purple
- Status colors: warning (yellow), success (green), danger (red), info (blue), secondary (gray)

### Layout:
- Container: `container py-4` - Main container with vertical padding
- Cards: Gradient header + white body
- Responsive: Mobile-first with Bootstrap grid

## Controller Integration

These views work with `app/Http/Controllers/Frontend/SkripsiSeminarController.php`:

```php
// Index - List all seminars
public function index()
{
    $skripsiSeminars = SkripsiSeminar::with(['application', 'created_by', 'media'])->get();
    return view('frontend.skripsiSeminars.index', compact('skripsiSeminars'));
}

// Create - Show form
public function create()
{
    // Check access via FormAccessService
    return view('frontend.skripsiSeminars.create', compact('activeApplication'));
}

// Store - Save new seminar
public function store(StoreSkripsiSeminarRequest $request)
{
    // Create application and seminar
    // Upload documents
    return redirect()->route('frontend.skripsi-seminars.index');
}

// Show - Display detail
public function show(SkripsiSeminar $skripsiSeminar)
{
    $skripsiSeminar->load('application', 'created_by');
    return view('frontend.skripsiSeminars.show', compact('skripsiSeminar'));
}

// Edit - Show edit form
public function edit(SkripsiSeminar $skripsiSeminar)
{
    $skripsiSeminar->load('application', 'created_by');
    return view('frontend.skripsiSeminars.edit', compact('skripsiSeminar'));
}

// Update - Save changes
public function update(UpdateSkripsiSeminarRequest $request, SkripsiSeminar $skripsiSeminar)
{
    // Update seminar
    // Replace documents if new ones uploaded
    return redirect()->route('frontend.skripsi-seminars.index');
}
```

## Routes

Routes are defined in `routes/web.php` under the `frontend` group:

```php
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    // Skripsi Seminar Routes
    Route::delete('skripsi-seminars/destroy', 'SkripsiSeminarController@massDestroy')->name('skripsi-seminars.massDestroy');
    Route::post('skripsi-seminars/media', 'SkripsiSeminarController@storeMedia')->name('skripsi-seminars.storeMedia');
    Route::post('skripsi-seminars/ckmedia', 'SkripsiSeminarController@storeCKEditorImages')->name('skripsi-seminars.storeCKEditorImages');
    Route::resource('skripsi-seminars', 'SkripsiSeminarController');
});
```

## Permissions

All views respect Laravel Gate permissions:
- `skripsi_seminar_access` - View list
- `skripsi_seminar_show` - View detail
- `skripsi_seminar_create` - Create new
- `skripsi_seminar_edit` - Edit existing
- `skripsi_seminar_delete` - Delete

## File Upload Handling

The controller uses Spatie Media Library with custom naming:

```php
// Store
if ($request->input('proposal_document', false)) {
    $skripsiSeminar->addMediaWithCustomName(
        storage_path('tmp/uploads/' . basename($request->input('proposal_document'))),
        'proposal_document'
    );
}

// Update (with replacement logic)
if ($request->input('proposal_document', false)) {
    if (! $skripsiSeminar->proposal_document || $request->input('proposal_document') !== $skripsiSeminar->proposal_document->file_name) {
        if ($skripsiSeminar->proposal_document) {
            $skripsiSeminar->proposal_document->delete();
        }
        $skripsiSeminar->addMediaWithCustomName(...);
    }
}
```

## Next Steps (Similar Views Needed)

Based on the PROSES_ALUR_SKRIPSI.md, similar views are needed for:
1. **MBKM Seminars** - `frontend/mbkmSeminars/*`
2. **Skripsi Defenses** - `frontend/skripsiDefenses/*`
3. **Application Schedules** - `frontend/applicationSchedules/*`
4. **Application Results** - `frontend/applicationResults/*`

## Testing Checklist

- [x] Views created with correct naming convention
- [x] Extended proper layout (`layouts.mahasiswa`)
- [x] Used design system CSS classes
- [x] Implemented responsive layout
- [x] Added form validation support
- [x] Integrated file upload UI
- [x] Added permission checks
- [x] Created empty states
- [x] Added status badges
- [x] JavaScript for file input labels

## Conclusion

All required views for Skripsi Seminar CRUD operations have been created with:
- Modern, consistent design
- Full functionality (list, create, show, edit)
- Proper integration with controller and routes
- Permission-based access control
- Responsive layout
- File upload support
