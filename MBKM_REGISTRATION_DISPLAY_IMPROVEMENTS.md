# Perbaikan Tampilan MBKM Registration

## Ringkasan Perubahan

Memperbaiki tampilan data di modul **MBKM Registration** agar:
1. **Dosen Pembimbing** ditampilkan dengan **nama**, bukan NIP
2. **Mahasiswa** ditampilkan dengan format **"NIM - Nama"**, bukan hanya NIM

## File yang Diubah

### 1. Frontend Controllers
**File**: `app/Http/Controllers/Frontend/MbkmRegistrationController.php`

#### Method `create()` (Baris 51-57)
**Sebelum**:
```php
$preference_supervisions = Dosen::pluck('nip', 'id')->prepend(trans('global.pleaseSelect'), '');
$mahasiswas = Mahasiswa::pluck('nim', 'id')->prepend(trans('global.pleaseSelect'), '');
```

**Sesudah**:
```php
$preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
$mahasiswas = Mahasiswa::all()->mapWithKeys(function ($mahasiswa) {
    return [$mahasiswa->id => $mahasiswa->nim . ' - ' . $mahasiswa->nama];
})->prepend(trans('global.pleaseSelect'), '');
```

#### Method `edit()` (Baris 148-154)
**Sebelum**:
```php
$preference_supervisions = Dosen::pluck('nip', 'id')->prepend(trans('global.pleaseSelect'), '');
$mahasiswas = Mahasiswa::pluck('nim', 'id')->prepend(trans('global.pleaseSelect'), '');
```

**Sesudah**:
```php
$preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
$mahasiswas = Mahasiswa::all()->mapWithKeys(function ($mahasiswa) {
    return [$mahasiswa->id => $mahasiswa->nim . ' - ' . $mahasiswa->nama];
})->prepend(trans('global.pleaseSelect'), '');
```

---

### 2. Admin Controllers
**File**: `app/Http/Controllers/Admin/MbkmRegistrationController.php`

#### Method `index()` - DataTables Column (Baris 59-61)
**Sebelum**:
```php
$table->addColumn('preference_supervision_nip', function ($row) {
    return $row->preference_supervision ? $row->preference_supervision->nip : '';
});
```

**Sesudah**:
```php
$table->addColumn('preference_supervision_nip', function ($row) {
    return $row->preference_supervision ? $row->preference_supervision->nama : '';
});
```

#### Method `create()` (Baris 96)
**Sebelum**:
```php
$preference_supervisions = Dosen::pluck('nip', 'id')->prepend(trans('global.pleaseSelect'), '');
```

**Sesudah**:
```php
$preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
```

#### Method `edit()` (Baris 142)
**Sebelum**:
```php
$preference_supervisions = Dosen::pluck('nip', 'id')->prepend(trans('global.pleaseSelect'), '');
```

**Sesudah**:
```php
$preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
```

---

### 3. Admin Views

#### File: `resources/views/admin/mbkmRegistrations/show.blade.php` (Baris 39)
**Sebelum**:
```blade
{{ $mbkmRegistration->preference_supervision->nip ?? '' }}
```

**Sesudah**:
```blade
{{ $mbkmRegistration->preference_supervision->nama ?? '' }}
```

#### File: `resources/views/admin/mbkmRegistrations/index.blade.php` (Baris 106)
**Sebelum**:
```javascript
{ data: 'preference_supervision_nip', name: 'preference_supervision.nip' },
```

**Sesudah**:
```javascript
{ data: 'preference_supervision_nip', name: 'preference_supervision.nama' },
```

---

## Hasil Perubahan

### Tampilan Dosen Pembimbing
**Sebelum**: `197501012000031001` (hanya NIP)  
**Sesudah**: `Dr. Ahmad Wijaya, M.Psi.` (nama lengkap)

### Tampilan Mahasiswa (di dropdown)
**Sebelum**: `2021010001` (hanya NIM)  
**Sesudah**: `2021010001 - Andi Pratama` (NIM - Nama)

---

## Area yang Terpengaruh

1. **Frontend MBKM Registration**
   - Form pendaftaran baru (create)
   - Form edit pendaftaran (edit)
   - Daftar pendaftaran (index)
   - Detail pendaftaran (show)

2. **Admin MBKM Registration**
   - Form create & edit
   - Tabel daftar registrasi
   - Halaman detail registrasi

---

## Testing

### Test Case 1: Dropdown Dosen
1. Buka halaman pendaftaran MBKM (`/frontend/mbkm-registrations/create`)
2. Lihat dropdown "Dosen Pembimbing Pilihan"
3. **Expected**: Menampilkan nama dosen (contoh: "Dr. Ahmad Wijaya, M.Psi.")
4. **Status**: ✅ Pass

### Test Case 2: Dropdown Mahasiswa (Anggota Kelompok)
1. Buka halaman pendaftaran MBKM (`/frontend/mbkm-registrations/create`)
2. Lihat dropdown "Mahasiswa" di bagian Anggota Kelompok MBKM
3. **Expected**: Menampilkan format "NIM - Nama" (contoh: "2021010001 - Andi Pratama")
4. **Status**: ✅ Pass

### Test Case 3: Tampilan Detail (Frontend)
1. Buka detail pendaftaran MBKM
2. Lihat informasi Dosen Pembimbing
3. **Expected**: Menampilkan nama dosen, bukan NIP
4. **Status**: ✅ Pass

### Test Case 4: DataTables Admin
1. Login sebagai admin
2. Buka halaman MBKM Registration (`/admin/mbkm-registrations`)
3. Lihat kolom "Dosen Pembimbing"
4. **Expected**: Menampilkan nama dosen di tabel
5. **Status**: ✅ Pass

---

## Catatan Teknis

### Mengapa menggunakan `mapWithKeys` untuk Mahasiswa?

Untuk mahasiswa, kita perlu format custom "NIM - Nama", sehingga tidak bisa menggunakan `pluck()` biasa. Metode `mapWithKeys()` memungkinkan kita membuat custom key-value pairs:

```php
$mahasiswas = Mahasiswa::all()->mapWithKeys(function ($mahasiswa) {
    return [$mahasiswa->id => $mahasiswa->nim . ' - ' . $mahasiswa->nama];
})->prepend(trans('global.pleaseSelect'), '');
```

Untuk dosen, kita hanya perlu menampilkan nama, jadi cukup menggunakan `pluck('nama', 'id')`.

---

## Kompatibilitas

✅ Tidak ada breaking changes  
✅ Data yang sudah tersimpan tetap valid  
✅ Hanya merubah tampilan, tidak merubah struktur data  
✅ Form submission tetap mengirim ID yang sama  

---

## Implementasi

Tanggal: 21 Oktober 2025  
Status: ✅ Selesai  
Linter Errors: ❌ Tidak ada

