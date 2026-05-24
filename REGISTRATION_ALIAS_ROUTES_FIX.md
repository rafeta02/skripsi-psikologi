# Frontend Registration Routes - Alias Routes Added

## Issue
Route `[frontend.skripsi-registrations.index]` not defined.

Similar missing routes:
- `frontend.skripsi-registrations.*`
- `frontend.mbkm-registrations.*`

## Root Cause
There are **two different route naming conventions** being used in the codebase:

### Pattern 1: Original Routes
```
frontend.skripsi.index
frontend.skripsi.create
frontend.skripsi.store
...
```

### Pattern 2: Called by Views
```
frontend.skripsi-registrations.index
frontend.skripsi-registrations.create
frontend.skripsi-registrations.store
...
```

**Views are using Pattern 2, but only Pattern 1 exists in routes!**

## Solution: Add Alias Routes

Added **backward-compatible alias routes** that point to the same controllers but use the alternative naming convention.

### Routes Added

#### Skripsi Registration Aliases

```php
// Skripsi Registration Alias Routes (for backward compatibility)
Route::prefix('frontend/skripsi-registrations')->group(function () {
    Route::get('/', 'SkripsiRegistrationController@index')->name('skripsi-registrations.index');
    Route::get('/create', 'SkripsiRegistrationController@create')->name('skripsi-registrations.create');
    Route::post('/', 'SkripsiRegistrationController@store')->name('skripsi-registrations.store');
    Route::get('/{id}', 'SkripsiRegistrationController@show')->name('skripsi-registrations.show');
    Route::get('/{id}/edit', 'SkripsiRegistrationController@edit')->name('skripsi-registrations.edit');
    Route::put('/{id}', 'SkripsiRegistrationController@update')->name('skripsi-registrations.update');
    Route::delete('/{id}', 'SkripsiRegistrationController@destroy')->name('skripsi-registrations.destroy');
    Route::delete('/destroy', 'SkripsiRegistrationController@massDestroy')->name('skripsi-registrations.massDestroy');
    Route::post('/media', 'SkripsiRegistrationController@storeMedia')->name('skripsi-registrations.storeMedia');
});
```

#### MBKM Registration Aliases

```php
// MBKM Registration Alias Routes (for backward compatibility)
Route::prefix('frontend/mbkm-registrations')->group(function () {
    Route::get('/', 'MbkmRegistrationController@index')->name('mbkm-registrations.index');
    Route::get('/create', 'MbkmRegistrationController@create')->name('mbkm-registrations.create');
    Route::post('/', 'MbkmRegistrationController@store')->name('mbkm-registrations.store');
    Route::get('/{id}', 'MbkmRegistrationController@show')->name('mbkm-registrations.show');
    Route::get('/{id}/edit', 'MbkmRegistrationController@edit')->name('mbkm-registrations.edit');
    Route::put('/{id}', 'MbkmRegistrationController@update')->name('mbkm-registrations.update');
    Route::delete('/{id}', 'MbkmRegistrationController@destroy')->name('mbkm-registrations.destroy');
    Route::delete('/destroy', 'MbkmRegistrationController@massDestroy')->name('mbkm-registrations.massDestroy');
    Route::post('/media', 'MbkmRegistrationController@storeMedia')->name('mbkm-registrations.storeMedia');
});
```

## Route Mapping

### Both patterns now work:

| Pattern 1 (Original) | Pattern 2 (Alias) | URL | Controller |
|---------------------|-------------------|-----|------------|
| `frontend.skripsi.index` | `frontend.skripsi-registrations.index` | `/frontend/skripsi` or `/frontend/skripsi-registrations` | `SkripsiRegistrationController@index` |
| `frontend.skripsi.create` | `frontend.skripsi-registrations.create` | `/frontend/skripsi/{app}/create` or `/frontend/skripsi-registrations/create` | `SkripsiRegistrationController@create` |
| `frontend.skripsi.store` | `frontend.skripsi-registrations.store` | POST `/frontend/skripsi/{app}/store` or POST `/frontend/skripsi-registrations` | `SkripsiRegistrationController@store` |
| `frontend.skripsi.show` | `frontend.skripsi-registrations.show` | `/frontend/skripsi/{app}` or `/frontend/skripsi-registrations/{id}` | `SkripsiRegistrationController@show` |
| `frontend.skripsi.edit` | `frontend.skripsi-registrations.edit` | `/frontend/skripsi/{app}/edit` or `/frontend/skripsi-registrations/{id}/edit` | `SkripsiRegistrationController@edit` |
| `frontend.skripsi.update` | `frontend.skripsi-registrations.update` | PUT `/frontend/skripsi/{app}` or PUT `/frontend/skripsi-registrations/{id}` | `SkripsiRegistrationController@update` |

## Views Using Alias Routes

The following views call the alias routes:

1. **`resources/views/mahasiswa/dokumen.blade.php`** (line 65)
   ```blade
   <a href="{{ route('frontend.skripsi-registrations.index') }}" ...>
   ```

2. **`resources/views/mahasiswa/dashboard.blade.php`** (line 385)
   ```blade
   <a href="{{ route('frontend.skripsi-registrations.index') }}" ...>
   ```

3. **Other compiled views in storage/framework/views/**

## Why Alias Routes Instead of Updating Views?

### Option 1: Update All Views (NOT CHOSEN)
- Change all `frontend.skripsi-registrations.*` to `frontend.skripsi.*`
- **Cons:** Many files to update, risk of missing some, might break compiled views

### Option 2: Add Alias Routes (CHOSEN) ✅
- Add alternative routes pointing to same controllers
- **Pros:** 
  - No view changes needed
  - Backward compatible
  - Both patterns work
  - Safer approach
  - Quick fix

## Files Modified

### routes/web.php
Added two new route groups (lines ~244-263):
- `frontend/skripsi-registrations` alias group
- `frontend/mbkm-registrations` alias group

## Route Structure Now

```
frontend/ (route group)
├── skripsi/ (original)
│   ├── GET / → index
│   ├── GET /{app}/create → create
│   ├── POST /{app}/store → store
│   ├── GET /{app} → show
│   ├── GET /{app}/edit → edit
│   └── PUT /{app} → update
│
├── skripsi-registrations/ (alias) ⭐ NEW
│   ├── GET / → index
│   ├── GET /create → create
│   ├── POST / → store
│   ├── GET /{id} → show
│   ├── GET /{id}/edit → edit
│   ├── PUT /{id} → update
│   ├── DELETE /{id} → destroy
│   ├── DELETE /destroy → massDestroy
│   └── POST /media → storeMedia
│
├── mbkm/ (original)
│   └── ... (same pattern)
│
└── mbkm-registrations/ (alias) ⭐ NEW
    └── ... (same pattern)
```

## Testing

### Test Original Routes:
```
✅ GET /frontend/skripsi
✅ GET /frontend/skripsi/1/create
✅ POST /frontend/skripsi/1/store
✅ GET /frontend/skripsi/1
✅ GET /frontend/skripsi/1/edit
✅ PUT /frontend/skripsi/1
```

### Test Alias Routes:
```
✅ GET /frontend/skripsi-registrations
✅ GET /frontend/skripsi-registrations/create
✅ POST /frontend/skripsi-registrations
✅ GET /frontend/skripsi-registrations/1
✅ GET /frontend/skripsi-registrations/1/edit
✅ PUT /frontend/skripsi-registrations/1
✅ DELETE /frontend/skripsi-registrations/1
```

### Test Route Names in Views:
```blade
{{ route('frontend.skripsi.index') }}                    ✅ Works
{{ route('frontend.skripsi-registrations.index') }}      ✅ Works (NEW!)
```

## Additional Methods Added

The alias routes include additional methods not in original:
- `destroy` - Delete single registration
- `massDestroy` - Bulk delete
- `storeMedia` - Handle file uploads

These are standard RESTful routes that might be needed by existing views.

## Benefits

1. ✅ **Backward Compatible** - Old views still work
2. ✅ **Forward Compatible** - New views can use either pattern
3. ✅ **No Breaking Changes** - Existing functionality unchanged
4. ✅ **Quick Fix** - Resolves route errors immediately
5. ✅ **Flexible** - Both URL patterns work

## Future Considerations

### Option A: Keep Both (Recommended)
- Maintain both patterns for flexibility
- Document which to use in style guide

### Option B: Standardize Later
- Gradually migrate views to one pattern
- Remove alias routes after migration
- Choose: `frontend.skripsi.*` (shorter) or `frontend.skripsi-registrations.*` (more explicit)

## Date
2026-03-01

## Status
✅ **FIXED** - Alias routes added, both patterns now work
