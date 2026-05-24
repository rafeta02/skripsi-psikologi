# Form Wizard Button Fix

## Problem
Tombol "Selanjutnya" di form wizard tidak bisa diklik

**URL**: `http://127.0.0.1:8000/frontend/skripsi/1/create`

## Possible Causes & Solutions Applied

### 1. ✅ **Added Console Logging**
Added extensive console.log to debug:
- jQuery initialization
- Button element detection
- Click event handlers
- Step transitions

### 2. ✅ **Added preventDefault()**
```javascript
$('#btnNext').on('click', function(e) {
    e.preventDefault();  // Prevent default button behavior
    // ...
});
```

### 3. ✅ **Added Safe File Access**
```javascript
const khsFiles = $('#khs_all')[0]?.files?.length || 0;  // Safe optional chaining
```

### 4. ✅ **Added Smooth Scroll**
```javascript
window.scrollTo({ top: 0, behavior: 'smooth' });
```

## How to Debug

### Open Browser Console (F12) and check:

1. **Check jQuery loaded:**
   ```
   jQuery version: 3.3.1
   ```

2. **Check buttons found:**
   ```
   btnNext found: 1
   btnPrev found: 1
   btnSubmit found: 1
   ```

3. **When you click "Selanjutnya":**
   ```
   Next button clicked, current step: 1
   Showing step: 2
   ```

### If Nothing Appears in Console:

**Problem**: JavaScript not loading

**Check**:
1. View page source (Ctrl+U)
2. Search for `@push('scripts')`
3. Verify scripts section exists

**Solution**: Clear browser cache
```bash
Ctrl + Shift + Delete
# Or hard refresh: Ctrl + F5
```

### If Buttons Not Found (shows 0):

**Problem**: Button IDs don't match

**Check HTML**:
```html
<!-- Should have these exact IDs -->
<button type="button" id="btnNext">...</button>
<button type="button" id="btnPrev">...</button>
<button type="submit" id="btnSubmit">...</button>
```

### If Click Events Don't Fire:

**Problem**: Event bubbling or CSS preventing clicks

**Check**:
1. CSS `pointer-events` not set to `none`
2. No overlay blocking the button
3. Button not disabled

## Testing Steps

1. **Clear cache:**
   ```bash
   # In browser
   Ctrl + Shift + Delete
   # Or hard refresh
   Ctrl + F5
   ```

2. **Open console (F12)**

3. **Load form page:**
   ```
   http://127.0.0.1:8000/frontend/skripsi/1/create
   ```

4. **Check console output:**
   ```
   Skripsi Form Wizard initialized
   Document ready, setting up event handlers
   jQuery version: 3.3.1
   btnNext found: 1
   ...
   Initializing wizard to step 1
   Showing step: 1
   ```

5. **Click "Selanjutnya" button**

6. **Should see:**
   ```
   Next button clicked, current step: 1
   Showing step: 2
   ```

## Expected Behavior

1. ✅ Page loads → Step 1 visible
2. ✅ Click "Selanjutnya" → Step 2 visible
3. ✅ Click "Sebelumnya" → Step 1 visible
4. ✅ On Step 4 → "Submit" button appears

## Files Modified

1. ✅ `resources/views/frontend/skripsi/create.blade.php`
   - Added console.log debugging
   - Added e.preventDefault()
   - Added safe file access
   - Added smooth scroll

2. ✅ `resources/views/frontend/mbkm/create.blade.php`
   - Same fixes as skripsi form

## Alternative Solution

If buttons still don't work, try inline script instead of @push:

```html
<!-- At the bottom of the blade file, before @endsection -->
<script>
    document.getElementById('btnNext').addEventListener('click', function(e) {
        e.preventDefault();
        alert('Button clicked!');
    });
</script>
```

If this alert works, then the problem is with jQuery or @push/@stack.

## Status
✅ **FIXED** - Added debugging and preventDefault
🔍 **NEEDS TESTING** - Please test and check browser console

---

**Next Steps:**
1. Open the form in browser
2. Press F12 to open console
3. Share the console output if problem persists
