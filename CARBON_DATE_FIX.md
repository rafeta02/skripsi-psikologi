# Carbon Date Parsing Error Fix

## Problem
Error: `The separation symbol could not be found Unexpected data found. Unexpected data found. Trailing data`

**Cause**: Carbon mutators trying to parse `now()` or Carbon instances as strings with specific format.

## Root Cause
When you do:
```php
Application::create([
    'submitted_at' => now(),  // This is Carbon instance
]);
```

The mutator `setSubmittedAtAttribute` tries to parse it again:
```php
Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), now())
// ERROR! Trying to parse Carbon instance as string
```

## Solution Applied

### Files Fixed:

1. **`app/Models/Application.php`** ✅
   - Fixed `setSubmittedAtAttribute()` mutator

2. **`app/Models/ApplicationSchedule.php`** ✅
   - Fixed `setWaktuAttribute()` mutator

3. **`app/Models/ApplicationAssignment.php`** ✅
   - Fixed `setAssignedAtAttribute()` mutator
   - Fixed `setRespondedAtAttribute()` mutator

### Pattern Used:
```php
public function setSubmittedAtAttribute($value)
{
    if (!$value) {
        $this->attributes['submitted_at'] = null;
        return;
    }
    
    // If value is already a Carbon instance or DateTime
    if ($value instanceof \DateTimeInterface) {
        $this->attributes['submitted_at'] = $value->format('Y-m-d H:i:s');
        return;
    }
    
    // If value is a string, try to parse it
    try {
        $this->attributes['submitted_at'] = Carbon::createFromFormat(
            config('panel.date_format') . ' ' . config('panel.time_format'), 
            $value
        )->format('Y-m-d H:i:s');
    } catch (\Exception $e) {
        // If parsing fails, try standard parse
        $this->attributes['submitted_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
```

## Other Models That May Need Fixing

If you encounter similar errors, check these models:

- **`User.php`** - `email_verified_at`
- **`Mahasiswa.php`** - `tanggal_lahir`
- **`Dosen.php`** - `tanggal_lahir`
- **`ApplicationResultReview.php`** - `revision_deadline`
- **`ApplicationResultSeminar.php`** - `revision_deadline`
- **`ApplicationResultDefense.php`** - `revision_deadline`

## How to Test

1. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

2. Try creating application:
```php
Application::create([
    'mahasiswa_id' => 1,
    'type' => 'skripsi',
    'stage' => 'registration',
    'status' => 'submitted',
    'submitted_at' => now(),  // Should work now
]);
```

## Prevention

**Best Practice**: Don't use mutators for date fields if you're using `$dates` or `$casts`:

```php
// Option 1: Remove mutator, use $casts
protected $casts = [
    'submitted_at' => 'datetime',
];

// Option 2: Keep mutator but handle all input types
public function setSubmittedAtAttribute($value)
{
    if ($value instanceof \DateTimeInterface) {
        $this->attributes['submitted_at'] = $value->format('Y-m-d H:i:s');
    } else {
        $this->attributes['submitted_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
```

## Status
✅ **FIXED** - Application form submission should work now!
