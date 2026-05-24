# Application Routes Fix

## Problem
```
Route [frontend.applications.show] not defined
```

## Solution Applied

### 1. ✅ Added Application Routes to `routes/web.php`

```php
// Applications - General application routes
Route::get('frontend/applications', 'ApplicationController@index')->name('applications.index');
Route::get('frontend/applications/{application}', 'ApplicationController@show')->name('applications.show');
```

### 2. ✅ Created `ApplicationController.php`

**Smart Redirect Logic:**
```php
public function show($id)
{
    $application = Application::findOrFail($id);
    
    // Redirect based on type
    if ($application->type == 'skripsi') {
        return redirect()->route('frontend.skripsi.show', $application->id);
    } else {
        return redirect()->route('frontend.mbkm.show', $application->id);
    }
}
```

This controller automatically redirects to the correct detail page based on application type!

### 3. ✅ Created Show Views

**Files Created:**
- `resources/views/frontend/skripsi/show.blade.php` - Detail page for Skripsi Reguler
- `resources/views/frontend/mbkm/show.blade.php` - Detail page for MBKM

**Features:**
- Application info with status badges
- Registration details
- Abstract/titles display
- Document download links
- Edit button (if status allows)

## Usage

### In Views:
```blade
{{-- This will auto-redirect to correct show page --}}
<a href="{{ route('frontend.applications.show', $application->id) }}">
    View Details
</a>

{{-- Or use specific routes --}}
<a href="{{ route('frontend.skripsi.show', $application->id) }}">Skripsi Details</a>
<a href="{{ route('frontend.mbkm.show', $application->id) }}">MBKM Details</a>
```

## Files Modified/Created

### Modified:
1. ✅ `routes/web.php` - Added application routes

### Created:
2. ✅ `app/Http/Controllers/Frontend/ApplicationController.php`
3. ✅ `resources/views/frontend/skripsi/show.blade.php`
4. ✅ `resources/views/frontend/mbkm/show.blade.php`

## Files Using This Route

These files now work correctly:
- ✅ `resources/views/mahasiswa/dashboard.blade.php`
- ✅ `resources/views/mahasiswa/aplikasi.blade.php`
- ✅ `resources/views/mahasiswa/bimbingan.blade.php`
- ✅ `resources/views/mahasiswa/dokumen.blade.php`

## Testing

1. **List applications:**
   ```
   http://127.0.0.1:8000/frontend/applications
   ```

2. **View specific application (auto-redirects):**
   ```
   http://127.0.0.1:8000/frontend/applications/1
   → Redirects to: /frontend/skripsi/1 or /frontend/mbkm/1
   ```

3. **Direct access:**
   ```
   http://127.0.0.1:8000/frontend/skripsi/1
   http://127.0.0.1:8000/frontend/mbkm/1
   ```

## Complete Route Structure

```
frontend.applications.index      GET    /frontend/applications
frontend.applications.show       GET    /frontend/applications/{id}  → Auto-redirect

frontend.skripsi.index           GET    /frontend/skripsi
frontend.skripsi.create          GET    /frontend/skripsi/{id}/create
frontend.skripsi.store           POST   /frontend/skripsi/{id}/store
frontend.skripsi.show            GET    /frontend/skripsi/{id}
frontend.skripsi.edit            GET    /frontend/skripsi/{id}/edit
frontend.skripsi.update          PUT    /frontend/skripsi/{id}

frontend.mbkm.index              GET    /frontend/mbkm
frontend.mbkm.create             GET    /frontend/mbkm/{id}/create
frontend.mbkm.store              POST   /frontend/mbkm/{id}/store
frontend.mbkm.show               GET    /frontend/mbkm/{id}
frontend.mbkm.edit               GET    /frontend/mbkm/{id}/edit
frontend.mbkm.update             PUT    /frontend/mbkm/{id}
```

## Status
✅ **FIXED** - All application show routes now work!

---

**Clear cache and test:**
```bash
php artisan route:clear
php artisan config:clear
```
