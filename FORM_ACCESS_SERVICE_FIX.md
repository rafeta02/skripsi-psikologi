# Fix: Form Access Service Method Call Error

## Error yang Terjadi
```
Call to undefined method App\Services\FormAccessService::canCreateApplication()
```

Error ini terjadi ketika user mencoba submit form MBKM Registration atau Skripsi Registration.

## Penyebab
Method `canCreateApplication()` **tidak ada** di `FormAccessService`, tetapi dipanggil di:
1. `MbkmRegistrationController::store()`
2. `SkripsiRegistrationController::store()`

## Solusi

### File yang Diperbaiki

#### 1. `app/Http/Controllers/Frontend/MbkmRegistrationController.php`

**Baris 66** - Method `store()`

**Sebelum**:
```php
$canCreate = $formAccessService->canCreateApplication(auth()->user()->mahasiswa_id);
```

**Sesudah**:
```php
$canCreate = $formAccessService->canAccessMbkmRegistration(auth()->user()->mahasiswa_id);
```

---

#### 2. `app/Http/Controllers/Frontend/SkripsiRegistrationController.php`

**Baris 59** - Method `store()`

**Sebelum**:
```php
$canCreate = $formAccessService->canCreateApplication(auth()->user()->mahasiswa_id);
```

**Sesudah**:
```php
$canCreate = $formAccessService->canAccessSkripsiRegistration(auth()->user()->mahasiswa_id);
```

---

## Method yang Tersedia di FormAccessService

File: `app/Services/FormAccessService.php`

### Available Methods:

1. ✅ `canAccessMbkmRegistration($mahasiswaId)` - Cek akses MBKM Registration
2. ✅ `canAccessSkripsiRegistration($mahasiswaId)` - Cek akses Skripsi Registration
3. ✅ `canAccessMbkmSeminar($mahasiswaId)` - Cek akses MBKM Seminar
4. ✅ `canAccessSkripsiSeminar($mahasiswaId)` - Cek akses Skripsi Seminar
5. ✅ `canAccessSkripsiDefense($mahasiswaId)` - Cek akses Skripsi Defense
6. ✅ `getAllowedForms($mahasiswaId)` - Get semua form yang diizinkan
7. ✅ `hasActiveApplication($mahasiswaId)` - Cek apakah ada aplikasi aktif
8. ✅ `getActiveApplication($mahasiswaId)` - Get aplikasi yang aktif

### ❌ NOT Available:
- `canCreateApplication()` - Method ini tidak pernah dibuat

---

## Fungsi Method yang Digunakan

### `canAccessMbkmRegistration($mahasiswaId)`
Mengecek apakah mahasiswa dapat mengakses form MBKM Registration dengan validasi:
- ✅ Mahasiswa baru dapat mendaftar
- ❌ Tidak bisa jika sudah ditolak dari MBKM (harus daftar jalur reguler)
- ❌ Tidak bisa jika sudah ada pendaftaran MBKM aktif
- ❌ Tidak bisa jika sudah memilih jalur Skripsi Reguler

### `canAccessSkripsiRegistration($mahasiswaId)`
Mengecek apakah mahasiswa dapat mengakses form Skripsi Registration dengan validasi:
- ✅ Mahasiswa baru dapat mendaftar
- ❌ Tidak bisa jika sudah ada pendaftaran Skripsi aktif
- ❌ Tidak bisa jika sudah memilih dan disetujui jalur MBKM

---

## Return Value

Semua method `canAccess*` mengembalikan array dengan format:

```php
[
    'allowed' => true/false,    // Boolean - apakah diizinkan
    'message' => 'Pesan error'  // String - pesan jika tidak diizinkan (null jika allowed)
]
```

Beberapa method juga mengembalikan `application` object:

```php
[
    'allowed' => true/false,
    'message' => 'Pesan error',
    'application' => $applicationObject  // Object Application yang terkait
]
```

---

## Testing

### Test Case 1: Submit MBKM Registration Form
1. Login sebagai mahasiswa
2. Akses `/frontend/mbkm-registrations/create`
3. Isi semua field yang diperlukan
4. Submit form
5. **Expected**: Form berhasil disubmit tanpa error
6. **Status**: ✅ Pass

### Test Case 2: Submit Skripsi Registration Form
1. Login sebagai mahasiswa
2. Akses `/frontend/skripsi-registrations/create`
3. Isi semua field yang diperlukan
4. Submit form
5. **Expected**: Form berhasil disubmit tanpa error
6. **Status**: ✅ Pass

### Test Case 3: Prevent Double Registration (MBKM)
1. Login sebagai mahasiswa yang sudah punya aplikasi MBKM aktif
2. Akses `/frontend/mbkm-registrations/create`
3. **Expected**: Redirect dengan error message
4. **Status**: ✅ Pass

### Test Case 4: Prevent Double Registration (Skripsi)
1. Login sebagai mahasiswa yang sudah punya aplikasi Skripsi aktif
2. Akses `/frontend/skripsi-registrations/create`
3. **Expected**: Redirect dengan error message
4. **Status**: ✅ Pass

---

## Verifikasi Controller Lain

Semua controller lain sudah menggunakan method yang benar:

✅ **MbkmSeminarController**: `canAccessMbkmSeminar()`  
✅ **SkripsiSeminarController**: `canAccessSkripsiSeminar()`  
✅ **SkripsiDefenseController**: `canAccessSkripsiDefense()`  

---

## Status

- **Tanggal**: 21 Oktober 2025
- **Status**: ✅ Selesai diperbaiki
- **Linter Errors**: ❌ Tidak ada
- **Breaking Changes**: ❌ Tidak ada
- **Impact**: Form submission MBKM dan Skripsi sekarang berfungsi normal

---

## Catatan

Error ini kemungkinan terjadi karena:
1. Method `canCreateApplication()` direncanakan tetapi belum diimplementasi
2. Atau copy-paste code dari tempat lain yang belum disesuaikan

Solusi yang diterapkan menggunakan method yang sudah ada dan berfungsi dengan baik di method `create()` pada controller yang sama, sehingga tidak ada perubahan logic - hanya konsistensi pemanggilan method.

