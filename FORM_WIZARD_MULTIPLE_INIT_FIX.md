# Form Wizard Multiple Initialization Fix

## Masalah
Form wizard (multiple step form) pada halaman:
- `http://127.0.0.1:8000/frontend/skripsi/1/create` (Skripsi Reguler)
- `http://127.0.0.1:8000/frontend/mbkm/1/create` (MBKM)

Tombol "Selanjutnya" dan "Sebelumnya" tidak berfungsi dengan benar.

## Penyebab

### ROOT CAUSE: @stack('scripts') tidak ada di layout!
Layout `mahasiswa.blade.php` hanya memiliki `@yield('scripts')`, tetapi form wizard menggunakan `@push('scripts')`.

**@push/@stack vs @section/@yield:**
- `@push('scripts')` → Harus dipasangkan dengan `@stack('scripts')` di layout
- `@section('scripts')` → Harus dipasangkan dengan `@yield('scripts')` di layout
- Keduanya TIDAK bisa di-mix!

### Secondary Issues:
1. **Multiple Initialization**: Script wizard bisa dieksekusi beberapa kali jika halaman ter-reload atau ada konflik dengan script lain
2. **Event Handler Conflict**: Event handlers bisa ditambahkan berkali-kali tanpa di-clear terlebih dahulu
3. **No Isolation**: Script tidak terisolasi dalam IIFE (Immediately Invoked Function Expression)

## Solusi

### CRITICAL FIX 1: Add @stack to Layout

#### resources/views/layouts/mahasiswa.blade.php

**Before:**
```blade
    @yield('styles')
</head>
```

**After:**
```blade
    @yield('styles')
    @stack('styles')
</head>
```

**Before:**
```blade
    @yield('scripts')
</body>
</html>
```

**After:**
```blade
    @yield('scripts')
    @stack('scripts')
</body>
</html>
```

**Why this is critical:**
- `@push('scripts')` di form wizard akan di-inject ke `@stack('scripts')` di layout
- Tanpa `@stack('scripts')`, semua script di `@push('scripts')` TIDAK akan dirender!
- `@yield('scripts')` hanya menerima dari `@section('scripts')`, BUKAN dari `@push('scripts')`

### CRITICAL FIX 2: Wrapped in IIFE with Strict Mode
```javascript
(function() {
    'use strict';
    // All code here...
})();
```
- Mengisolasi scope
- Mencegah polusi global namespace
- Mengaktifkan strict mode untuk error detection yang lebih baik

### 2. Guard Against Multiple Initialization
```javascript
if (window.skripsiFormInitialized) {
    console.warn('Skripsi form already initialized');
    return;
}
window.skripsiFormInitialized = true;
```
- Mengecek flag global sebelum init
- Set flag setelah init untuk mencegah double execution

### 3. Remove Existing Event Handlers
```javascript
// Remove any existing event handlers
btnNext.off('click');
btnPrev.off('click');
form.off('submit');

// Then attach fresh handlers
btnNext.on('click', function(e) { ... });
```
- Clear semua handlers sebelum attach yang baru
- Mencegah multiple click handlers

### 4. Robust Event Prevention
```javascript
btnNext.on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    // ... logic ...
    return false;
});
```
- Triple safety: `preventDefault()`, `stopPropagation()`, dan `return false`

### 5. Better Element Existence Checking
```javascript
if (btnNext.length === 0) {
    console.error('[Skripsi Wizard] ERROR: btnNext not found!');
    return;
}
```
- Early return jika elemen tidak ditemukan
- Mencegah errors di console

### 6. Enhanced showStep() Function
```javascript
function showStep(step) {
    // Hide all sections first
    $('.form-section').removeClass('active').hide();
    
    // Show target section with animation
    $(`.form-section[data-section="${step}"]`).addClass('active').fadeIn(300);
    
    // Smooth scroll
    $('html, body').animate({ scrollTop: 0 }, 300);
}
```
- Explicit hide() sebelum show
- Smooth fade-in animation
- jQuery animate untuk smooth scroll

### 7. Enhanced Logging
```javascript
console.log('[Skripsi Wizard] Initializing...');
console.log('[Skripsi Wizard] Next clicked, step:', currentStep);
```
- Prefix log messages dengan nama wizard
- Lebih mudah untuk debugging

## Files Modified

### 1. resources/views/layouts/mahasiswa.blade.php ⭐ CRITICAL
**Added @stack directives:**
- Added `@stack('styles')` after `@yield('styles')` in `<head>` section
- Added `@stack('scripts')` after `@yield('scripts')` before `</body>`

**Why critical:**
Without `@stack('scripts')`, all scripts in `@push('scripts')` blocks from child views will NOT be rendered! This was the root cause of the wizard buttons not working.

### 2. resources/views/frontend/skripsi/create.blade.php
- Wrapped script dalam IIFE
- Added initialization guard
- Removed existing handlers before attaching
- Enhanced showStep() with explicit hide/show
- Added element existence checking
- Enhanced logging

### 3. resources/views/frontend/mbkm/create.blade.php
- Same changes as skripsi/create.blade.php
- Adjusted for 5-step wizard (vs 4-step in Skripsi)
- Different form ID: `#mbkmForm`
- Different flag: `window.mbkmFormInitialized`

## Testing Checklist

### Skripsi Reguler Form
- [ ] Navigate to `/frontend/skripsi/1/create`
- [ ] Open browser console (F12)
- [ ] Verify log: `[Skripsi Wizard] Initializing...`
- [ ] Click "Selanjutnya" button → Should go to Step 2
- [ ] Click "Sebelumnya" button → Should go back to Step 1
- [ ] Navigate through all 4 steps
- [ ] Fill form and submit
- [ ] Check console for any JavaScript errors

### MBKM Form
- [ ] Navigate to `/frontend/mbkm/1/create`
- [ ] Open browser console (F12)
- [ ] Verify log: `[MBKM Wizard] Initializing...`
- [ ] Click "Selanjutnya" button → Should go to Step 2
- [ ] Click "Sebelumnya" button → Should go back to Step 1
- [ ] Navigate through all 5 steps
- [ ] Fill form and submit
- [ ] Check console for any JavaScript errors

### Multiple Page Loads
- [ ] Refresh page multiple times
- [ ] Check console for "already initialized" warning (should appear on 2nd+ init)
- [ ] Verify buttons still work after refresh

## Console Output Example

### Normal Execution
```
[Skripsi Wizard] Initializing...
[Skripsi Wizard] Document ready
[Skripsi Wizard] jQuery version: 3.x.x
[Skripsi Wizard] Elements found: {btnNext: 1, btnPrev: 1, btnSubmit: 1, form: 1}
[Skripsi Wizard] Initializing to step 1
[Skripsi Wizard] Showing step: 1
[Skripsi Wizard] Initialization complete!
```

### Click Next
```
[Skripsi Wizard] Next clicked, step: 1
[Skripsi Wizard] Showing step: 2
```

### Click Previous
```
[Skripsi Wizard] Prev clicked, step: 2
[Skripsi Wizard] Showing step: 1
```

## Related Files
- `resources/views/frontend/skripsi/create.blade.php` - Skripsi Reguler wizard
- `resources/views/frontend/mbkm/create.blade.php` - MBKM wizard
- `public/css/form-components.css` - CSS for wizard styling
- `app/Http/Controllers/Frontend/SkripsiRegistrationController.php` - Backend controller
- `app/Http/Controllers/Frontend/MbkmRegistrationController.php` - Backend controller

## Best Practices Applied

1. ✅ **IIFE Pattern**: Isolate scope
2. ✅ **Initialization Guard**: Prevent multiple init
3. ✅ **Clean Handlers**: Remove before attach
4. ✅ **Triple Safety**: preventDefault + stopPropagation + return false
5. ✅ **Existence Check**: Verify elements before use
6. ✅ **Enhanced Logging**: Prefixed and detailed
7. ✅ **Smooth Animations**: fadeIn + jQuery animate
8. ✅ **Explicit Hide/Show**: Clear state management
9. ✅ **Fallback Support**: Check for SweetAlert2 before use
10. ✅ **Consistent Pattern**: Same fix for both forms

## Date
2026-03-01
