# CKEditor Validation Fix - "Invalid Form Control Not Focusable"

## Problem
Error message: **"An invalid form control with name='report_text' is not focusable"**

### Root Cause
This HTML5 validation error occurs when:
1. A textarea has the `required` attribute
2. The textarea is hidden (`display: none`) by CKEditor
3. User tries to submit the form
4. Browser can't focus on the hidden field to show validation error

```html
<!-- ❌ PROBLEM: -->
<textarea class="form-control ckeditor" name="report_text" required>
```

When CKEditor initializes, it:
- Hides the original textarea (`display: none`)
- Creates a rich text editor in its place
- Browser can't focus on hidden textarea for validation
- Form submission blocked with error

## Solution

### 1. Remove `required` Attribute from Textarea ✅

**File**: `resources/views/frontend/applicationReports/create.blade.php`

**Before:**
```html
<textarea class="form-control ckeditor" name="report_text" id="report_text" required>
```

**After:**
```html
<textarea class="form-control ckeditor" name="report_text" id="report_text">
```

### 2. Add Custom JavaScript Validation ✅

Added client-side validation to check CKEditor content before form submission:

```javascript
// Form validation for CKEditor
$('form').on('submit', function(e) {
  var reportText = $('#report_text').val().trim();
  
  // Check if CKEditor content is empty (after stripping HTML tags)
  var tempDiv = document.createElement('div');
  tempDiv.innerHTML = reportText;
  var textContent = (tempDiv.textContent || tempDiv.innerText || '').trim();
  
  if (!textContent) {
    e.preventDefault();
    alert('Deskripsi Kendala harus diisi!');
    
    // Try to focus on CKEditor or fallback textarea
    var editorElement = document.querySelector('.ck-editor');
    if (editorElement) {
      editorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
      $('#report_text').focus();
    }
    return false;
  }
});
```

**Features:**
- ✅ Strips HTML tags to check actual text content
- ✅ Shows user-friendly alert message in Indonesian
- ✅ Scrolls to CKEditor for visual feedback
- ✅ Falls back to textarea if CKEditor not initialized
- ✅ Prevents form submission if empty

### 3. Add Server-Side Validation ✅

**File**: `app/Http/Requests/StoreApplicationReportRequest.php`

```php
public function rules()
{
    return [
        'application_id' => [
            'required',
            'exists:applications,id',
        ],
        'report_text' => [
            'required',      // Server-side validation
            'string',
        ],
        'report_document' => [
            'array',
        ],
        'period' => [
            'string',
            'nullable',
        ],
    ];
}
```

**File**: `app/Http/Requests/UpdateApplicationReportRequest.php`

Same validation rules added for update requests.

## Files Modified

1. ✅ `resources/views/frontend/applicationReports/create.blade.php`
   - Removed `required` from textarea
   - Added custom JavaScript validation

2. ✅ `app/Http/Requests/StoreApplicationReportRequest.php`
   - Added server-side validation for `report_text`
   - Added validation for `application_id`

3. ✅ `app/Http/Requests/UpdateApplicationReportRequest.php`
   - Added server-side validation for `report_text`
   - Added validation for `application_id`

## How It Works Now

### Validation Flow

```
1. User fills form and clicks Submit
   ↓
2. JavaScript validation runs
   ↓
3. Check if CKEditor content is empty
   ↓
4a. IF EMPTY:
    - Prevent form submission
    - Show alert: "Deskripsi Kendala harus diisi!"
    - Scroll to CKEditor field
    - User can fix and resubmit
   ↓
4b. IF NOT EMPTY:
    - Allow form submission
    - Server validates with Laravel Request
    - If server validation fails, show errors
    - If passes, save to database
```

### Edge Cases Handled

1. **CKEditor has only HTML tags (no text)**:
   - Strips HTML to check actual content
   - `<p><br></p>` is considered empty ✅

2. **CKEditor not initialized (fallback textarea)**:
   - Validation still works on plain textarea
   - Focuses on textarea if editor not found ✅

3. **JavaScript disabled**:
   - Server-side validation still enforces requirement
   - Laravel returns validation error ✅

4. **Multiple spaces/whitespace only**:
   - `.trim()` removes whitespace
   - Considered empty ✅

## Testing

### To Test Validation Works:

1. **Test Empty Submission:**
   - Open form
   - Leave "Deskripsi Kendala" empty
   - Click "Kirim Laporan"
   - Should see: Alert "Deskripsi Kendala harus diisi!"
   - Page should scroll to editor
   - Form should NOT submit

2. **Test HTML-Only Submission:**
   - Open form
   - Click in CKEditor
   - Press Enter few times (creates `<p><br></p>`)
   - Click "Kirim Laporan"
   - Should see: Alert (field considered empty)
   - Form should NOT submit

3. **Test Valid Submission:**
   - Open form
   - Type actual text in CKEditor
   - Click "Kirim Laporan"
   - Should submit successfully
   - No alert shown

4. **Test Fallback (if CKEditor fails):**
   - If CKEditor doesn't load
   - Textarea should be visible
   - Leave it empty and submit
   - Should see alert
   - Type text and submit
   - Should work

### Browser Console Testing:

```javascript
// In browser console, test the validation:

// Test 1: Get textarea value
$('#report_text').val()

// Test 2: Check if empty after stripping HTML
var tempDiv = document.createElement('div');
tempDiv.innerHTML = $('#report_text').val();
console.log('Text content:', (tempDiv.textContent || '').trim());

// Test 3: Trigger form submit
$('form').submit();
```

## Benefits

### Before Fix:
- ❌ Confusing browser error message
- ❌ Form can't be submitted
- ❌ No indication what's wrong
- ❌ User stuck with no feedback

### After Fix:
- ✅ Clear Indonesian error message
- ✅ Visual feedback (scroll to field)
- ✅ Client-side validation (fast feedback)
- ✅ Server-side validation (security)
- ✅ Works with or without CKEditor
- ✅ Better user experience

## Best Practices Applied

1. **Progressive Enhancement**:
   - Works with JavaScript (best UX)
   - Works without JavaScript (server validation)

2. **Defensive Programming**:
   - Checks for CKEditor existence
   - Falls back to textarea
   - Strips HTML before validation

3. **User Experience**:
   - Clear error messages in user's language
   - Visual feedback with scroll
   - Prevents frustration

4. **Security**:
   - Client-side for UX
   - Server-side for security
   - Never trust client alone

## Related Issues

This fix also prevents:
- "Element is not focusable" errors
- "Form control is not valid" errors
- Silent form submission failures
- Confusion when CKEditor appears empty but has HTML

## Troubleshooting

### Issue: Still getting "not focusable" error
**Solution**: Clear browser cache and reload page

### Issue: Validation not showing
**Solution**: Check browser console for JavaScript errors

### Issue: Form submits even when empty
**Solution**: Verify JavaScript validation code is present in `@section('scripts')`

### Issue: Server returns validation error
**Solution**: Check that Request validation rules include `report_text`

## Summary

The fix replaces HTML5's native `required` attribute (which doesn't work with CKEditor) with a custom JavaScript validation that:
- Properly checks CKEditor content
- Provides clear user feedback
- Maintains server-side validation
- Handles all edge cases gracefully

Form validation now works seamlessly with CKEditor! ✅

