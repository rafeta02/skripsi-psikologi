# Frontend Routes Fix - Skripsi Seminars, MBKM Seminars, Skripsi Defenses

## Issue
Route `[frontend.skripsi-seminars.index]` not defined.

Similar missing routes:
- `frontend.skripsi-seminars.*`
- `frontend.mbkm-seminars.*`
- `frontend.skripsi-defenses.*`

## Root Cause
These routes existed in the `admin` route group but were being called from frontend views (mahasiswa dashboard, mahasiswa dokumen) with the `frontend.` prefix.

## Solution
Added resourceful routes for Skripsi Seminars, MBKM Seminars, and Skripsi Defenses to the `frontend` route group.

### Routes Added to `frontend` Group

```php
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    // ... existing routes ...
    
    // Skripsi Seminar Routes
    Route::delete('skripsi-seminars/destroy', 'SkripsiSeminarController@massDestroy')->name('skripsi-seminars.massDestroy');
    Route::post('skripsi-seminars/media', 'SkripsiSeminarController@storeMedia')->name('skripsi-seminars.storeMedia');
    Route::post('skripsi-seminars/ckmedia', 'SkripsiSeminarController@storeCKEditorImages')->name('skripsi-seminars.storeCKEditorImages');
    Route::resource('skripsi-seminars', 'SkripsiSeminarController');
    
    // MBKM Seminar Routes
    Route::delete('mbkm-seminars/destroy', 'MbkmSeminarController@massDestroy')->name('mbkm-seminars.massDestroy');
    Route::post('mbkm-seminars/media', 'MbkmSeminarController@storeMedia')->name('mbkm-seminars.storeMedia');
    Route::post('mbkm-seminars/ckmedia', 'MbkmSeminarController@storeCKEditorImages')->name('mbkm-seminars.storeCKEditorImages');
    Route::resource('mbkm-seminars', 'MbkmSeminarController');
    
    // Skripsi Defense Routes
    Route::delete('skripsi-defenses/destroy', 'SkripsiDefenseController@massDestroy')->name('skripsi-defenses.massDestroy');
    Route::post('skripsi-defenses/media', 'SkripsiDefenseController@storeMedia')->name('skripsi-defenses.storeMedia');
    Route::post('skripsi-defenses/ckmedia', 'SkripsiDefenseController@storeCKEditorImages')->name('skripsi-defenses.storeCKEditorImages');
    Route::resource('skripsi-defenses', 'SkripsiDefenseController');
});
```

### Generated Routes

#### Skripsi Seminars (`frontend.skripsi-seminars.*`)
- `GET /skripsi-seminars` → `SkripsiSeminarController@index` → `frontend.skripsi-seminars.index`
- `GET /skripsi-seminars/create` → `SkripsiSeminarController@create` → `frontend.skripsi-seminars.create`
- `POST /skripsi-seminars` → `SkripsiSeminarController@store` → `frontend.skripsi-seminars.store`
- `GET /skripsi-seminars/{id}` → `SkripsiSeminarController@show` → `frontend.skripsi-seminars.show`
- `GET /skripsi-seminars/{id}/edit` → `SkripsiSeminarController@edit` → `frontend.skripsi-seminars.edit`
- `PUT/PATCH /skripsi-seminars/{id}` → `SkripsiSeminarController@update` → `frontend.skripsi-seminars.update`
- `DELETE /skripsi-seminars/{id}` → `SkripsiSeminarController@destroy` → `frontend.skripsi-seminars.destroy`
- `DELETE /skripsi-seminars/destroy` → `SkripsiSeminarController@massDestroy` → `frontend.skripsi-seminars.massDestroy`
- `POST /skripsi-seminars/media` → `SkripsiSeminarController@storeMedia` → `frontend.skripsi-seminars.storeMedia`
- `POST /skripsi-seminars/ckmedia` → `SkripsiSeminarController@storeCKEditorImages` → `frontend.skripsi-seminars.storeCKEditorImages`

#### MBKM Seminars (`frontend.mbkm-seminars.*`)
Same pattern as Skripsi Seminars but for MBKM

#### Skripsi Defenses (`frontend.skripsi-defenses.*`)
Same pattern as Skripsi Seminars but for Defenses

## Files Modified

### routes/web.php
Added three new resource route groups within the `frontend` route group (line ~254-277).

## Controllers Referenced

These controllers should exist in `app/Http/Controllers/Frontend/`:
- ✅ `SkripsiSeminarController.php` (already exists)
- ⚠️ `MbkmSeminarController.php` (needs verification)
- ⚠️ `SkripsiDefenseController.php` (needs verification)

## Views Referenced

These views are calling the routes:
- `resources/views/mahasiswa/dashboard.blade.php` (line 144)
- `resources/views/mahasiswa/dokumen.blade.php` (line 69)

## Alur Skripsi Implementation

These routes are part of the complete thesis flow:

1. **Registration** (Phase 1)
   - `frontend.skripsi.create` / `frontend.mbkm.create`
   - `frontend.skripsi.store` / `frontend.mbkm.store`

2. **Seminar** (Phase 2) ⭐ **JUST ADDED**
   - `frontend.skripsi-seminars.index`
   - `frontend.skripsi-seminars.create`
   - `frontend.mbkm-seminars.index`
   - `frontend.mbkm-seminars.create`

3. **Defense** (Phase 3) ⭐ **JUST ADDED**
   - `frontend.skripsi-defenses.index`
   - `frontend.skripsi-defenses.create`

4. **Grading** (Phase 4)
   - Still needs implementation

## Testing

Test the following routes:
- `http://127.0.0.1:8000/skripsi-seminars` → Should load seminar index page
- `http://127.0.0.1:8000/skripsi-seminars/create` → Should load seminar create form
- `http://127.0.0.1:8000/mbkm-seminars` → Should load MBKM seminar index
- `http://127.0.0.1:8000/skripsi-defenses` → Should load defense index

## Date
2026-03-01
