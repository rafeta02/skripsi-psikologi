# MBKM Revision Request Implementation

## Ringkasan
Implementasi fitur permintaan revisi untuk pendaftaran MBKM dan Skripsi. Mahasiswa sekarang dapat melihat notifikasi revisi yang diminta oleh admin dan dapat merevisi pendaftaran mereka.

## Perubahan yang Dilakukan

### 1. Database Migration
**File:** `database/migrations/2025_10_21_163401_add_admin_fields_to_mbkm_registrations_table.php`

Menambahkan 3 field baru ke tabel `mbkm_registrations`:
- `approval_date` (datetime, nullable) - Tanggal persetujuan
- `rejection_reason` (text, nullable) - Alasan penolakan
- `revision_notes` (text, nullable) - Catatan revisi dari admin

### 2. Model Update
**File:** `app/Models/MbkmRegistration.php`

Menambahkan field baru ke `$fillable` array:
```php
'approval_date',
'rejection_reason', 
'revision_notes',
```

### 3. Dashboard Mahasiswa - Controller
**File:** `app/Http/Controllers/Mahasiswa/DashboardController.php`

#### Perubahan pada method `determinePhase()`:
- Menambahkan status `'revision_requested'` ke dalam query `activeApplication`
- Sekarang query: `->whereIn('status', ['submitted', 'approved', 'scheduled', 'done', 'revision_requested'])`

#### Perubahan pada method `index()`:
- Menambahkan status `'revision_requested'` ke dalam statistik `activeApplications`
- Menambahkan logic untuk memuat `revision_notes` dari tabel `MbkmRegistration` atau `SkripsiRegistration` jika status adalah `revision_requested`

```php
// Load revision_notes from corresponding registration table
if ($activeApplication->status == 'revision_requested') {
    if ($activeApplication->type == 'mbkm') {
        $mbkmReg = MbkmRegistration::where('application_id', $activeApplication->id)->first();
        if ($mbkmReg) {
            $activeApplication->revision_notes = $mbkmReg->revision_notes;
        }
    } elseif ($activeApplication->type == 'skripsi') {
        $skripsiReg = SkripsiRegistration::where('application_id', $activeApplication->id)->first();
        if ($skripsiReg) {
            $activeApplication->revision_notes = $skripsiReg->revision_notes;
        }
    }
}
```

#### Perubahan pada method `aplikasi()`:
- Menambahkan loop untuk memuat `revision_notes` untuk setiap aplikasi yang berstatus `revision_requested`

### 4. Dashboard Mahasiswa - View
**File:** `resources/views/mahasiswa/dashboard.blade.php`

#### Penambahan Alert Revisi:
Menambahkan alert khusus yang muncul di bagian atas dashboard jika ada aplikasi dengan status `revision_requested`:

```blade
@if($activeApplication && $activeApplication->status == 'revision_requested')
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Revisi Diperlukan!</h5>
        <p>Admin telah meminta Anda untuk merevisi aplikasi <strong>{{ strtoupper($activeApplication->type) }}</strong> Anda.</p>
        @if(isset($activeApplication->revision_notes) && $activeApplication->revision_notes)
            <hr>
            <p class="mb-0"><strong>Catatan Revisi:</strong><br>{{ $activeApplication->revision_notes }}</p>
        @endif
        <hr>
        @if($activeApplication->type == 'mbkm')
            <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Revisi Pendaftaran MBKM
            </a>
        @elseif($activeApplication->type == 'skripsi')
            <a href="{{ route('frontend.skripsi-registrations.index') }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Revisi Pendaftaran Skripsi
            </a>
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
```

#### Badge Status Update:
Menambahkan badge untuk status `revision_requested` di 2 lokasi:
1. Di bagian "Aplikasi Aktif"
2. Di tabel "Aplikasi Terbaru"

```blade
@elseif($activeApplication->status == 'revision_requested')
    <span class="badge badge-warning"><i class="fas fa-edit"></i> Perlu Revisi</span>
```

### 5. Halaman Aplikasi Mahasiswa - View
**File:** `resources/views/mahasiswa/aplikasi.blade.php`

#### Perubahan Kolom Status:
Menambahkan badge untuk status `revision_requested`

#### Perubahan Kolom Catatan:
Menampilkan `revision_notes` jika status adalah `revision_requested`:
```blade
<td>
    @if($app->status == 'revision_requested' && isset($app->revision_notes))
        <span class="text-warning"><i class="fas fa-exclamation-circle"></i> {{ Str::limit($app->revision_notes, 50) }}</span>
    @else
        {{ $app->notes ?? '-' }}
    @endif
</td>
```

#### Perubahan Kolom Aksi:
Menambahkan tombol "Revisi" yang menonjol untuk aplikasi dengan status `revision_requested`:
```blade
@if($app->status == 'revision_requested')
    @if($app->type == 'mbkm')
        <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i> Revisi
        </a>
    @elseif($app->type == 'skripsi')
        <a href="{{ route('frontend.skripsi-registrations.index') }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i> Revisi
        </a>
    @endif
@endif
```

### 6. MBKM Registration Index - View
**File:** `resources/views/frontend/mbkmRegistrations/index.blade.php`

#### Alert Revisi di Halaman Index:
Menambahkan alert yang muncul jika ada pendaftaran MBKM yang memerlukan revisi:
```blade
@php
    $hasRevisionRequest = $mbkmRegistrations->contains(function($reg) {
        return $reg->application->status == 'revision_requested';
    });
@endphp

@if($hasRevisionRequest)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Revisi Diperlukan!</h5>
        <p>Admin telah meminta Anda untuk merevisi salah satu atau lebih pendaftaran MBKM Anda. Silakan periksa tabel di bawah dan klik tombol <strong>Edit</strong> untuk merevisi.</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
```

#### Badge dan Catatan di Tabel:
Menambahkan badge dan menampilkan preview revision_notes di kolom status:
```blade
@elseif($mbkmRegistration->application->status == 'revision_requested')
    <span class="badge badge-warning">
        <i class="fas fa-edit mr-1"></i> Perlu Revisi
    </span>
    @if($mbkmRegistration->revision_notes)
        <br><small class="text-muted">{{ Str::limit($mbkmRegistration->revision_notes, 50) }}</small>
    @endif
@endif
```

#### Tombol Edit yang Menonjol:
Tombol edit berubah menjadi warning dengan label "Revisi" untuk aplikasi yang memerlukan revisi:
```blade
@if($mbkmRegistration->application->status == 'revision_requested')
    <a class="btn btn-xs btn-warning" href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" title="Revisi Sekarang" style="font-weight: bold;">
        <i class="fas fa-edit"></i> Revisi
    </a>
@else
    <a class="btn btn-xs btn-info" href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@endif
```

### 7. MBKM Registration Edit - View
**File:** `resources/views/frontend/mbkmRegistrations/edit.blade.php`

Menambahkan alert di bagian atas form edit yang menampilkan catatan revisi dari admin:
```blade
@if($mbkmRegistration->application->status == 'revision_requested' && $mbkmRegistration->revision_notes)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Catatan Revisi dari Admin</h5>
        <p class="mb-0">{{ $mbkmRegistration->revision_notes }}</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
```

### 8. MBKM Registration Controller - Frontend
**File:** `app/Http/Controllers/Frontend/MbkmRegistrationController.php`

#### Method `update()`:
Menambahkan logic untuk mengubah status kembali ke `submitted` dan menghapus `revision_notes` ketika mahasiswa submit ulang setelah revisi:

```php
public function update(UpdateMbkmRegistrationRequest $request, MbkmRegistration $mbkmRegistration)
{
    $mbkmRegistration->update($request->all());
    
    // If status was revision_requested, change it back to submitted and clear revision_notes
    if ($mbkmRegistration->application->status == 'revision_requested') {
        $mbkmRegistration->application->update(['status' => 'submitted']);
        $mbkmRegistration->update(['revision_notes' => null]);
    }
    
    // ... rest of the update logic
}
```

### 9. Skripsi Registration Controller - Frontend
**File:** `app/Http/Controllers/Frontend/SkripsiRegistrationController.php`

#### Method `update()`:
Menambahkan logic yang sama seperti MBKM untuk mengubah status kembali ke `submitted`:

```php
public function update(UpdateSkripsiRegistrationRequest $request, SkripsiRegistration $skripsiRegistration)
{
    $skripsiRegistration->update($request->all());
    
    // If status was revision_requested, change it back to submitted and clear revision_notes
    if ($skripsiRegistration->application->status == 'revision_requested') {
        $skripsiRegistration->application->update(['status' => 'submitted']);
        $skripsiRegistration->update(['revision_notes' => null]);
    }
    
    // ... rest of the update logic
}
```

### 10. Admin Controller - MBKM
**File:** `app/Http/Controllers/Admin/MbkmRegistrationController.php`

Menambahkan 3 method baru untuk admin actions:
- `approve()` - Menyetujui pendaftaran
- `reject()` - Menolak pendaftaran
- `requestRevision()` - Meminta revisi

### 11. Routes
**File:** `routes/web.php`

Menambahkan routes untuk admin actions:
```php
Route::post('mbkm-registrations/{id}/approve', 'MbkmRegistrationController@approve')->name('mbkm-registrations.approve');
Route::post('mbkm-registrations/{id}/reject', 'MbkmRegistrationController@reject')->name('mbkm-registrations.reject');
Route::post('mbkm-registrations/{id}/request-revision', 'MbkmRegistrationController@requestRevision')->name('mbkm-registrations.request-revision');
```

## Alur Kerja (Workflow)

### 1. Admin Meminta Revisi
1. Admin membuka halaman detail MBKM registration
2. Admin klik tombol "Request Revision"
3. Admin mengisi catatan revisi di modal
4. Sistem mengubah status aplikasi menjadi `revision_requested`
5. Sistem menyimpan catatan revisi ke field `revision_notes`

### 2. Mahasiswa Melihat Permintaan Revisi
1. Mahasiswa login dan membuka dashboard
2. Muncul **Alert Warning** di bagian atas dashboard yang menampilkan:
   - Informasi bahwa revisi diperlukan
   - Catatan revisi dari admin
   - Tombol untuk merevisi
3. Status aplikasi di dashboard menampilkan badge "Perlu Revisi"
4. Di halaman "Aplikasi Saya":
   - Status menampilkan badge "Perlu Revisi"
   - Kolom catatan menampilkan preview revision_notes
   - Muncul tombol "Revisi" berwarna warning

### 3. Mahasiswa Melakukan Revisi
1. Mahasiswa klik tombol "Revisi" dari dashboard atau halaman aplikasi
2. Sistem redirect ke halaman index MBKM registration
3. Di halaman index, muncul alert warning di atas tabel
4. Di tabel, pendaftaran yang perlu revisi ditandai dengan:
   - Badge "Perlu Revisi"
   - Preview catatan revisi
   - Tombol edit berwarna warning dengan label "Revisi"
5. Mahasiswa klik tombol "Revisi"
6. Di halaman edit, muncul **Alert Warning** di atas form yang menampilkan catatan revisi lengkap dari admin
7. Mahasiswa melakukan perubahan sesuai catatan revisi
8. Mahasiswa klik "Update"

### 4. Sistem Memproses Update
1. Sistem menyimpan perubahan data
2. Sistem mengubah status aplikasi dari `revision_requested` kembali ke `submitted`
3. Sistem menghapus `revision_notes` (set null)
4. Mahasiswa di-redirect ke halaman index
5. Alert warning hilang
6. Status berubah menjadi "Menunggu" (submitted)

### 5. Admin Mereview Ulang
1. Admin melihat aplikasi kembali masuk ke daftar "Pending Approvals"
2. Admin dapat menyetujui atau meminta revisi lagi

## Status Aplikasi

### Status yang Tersedia:
- `submitted` - Baru diajukan / Sudah direvisi, menunggu review
- `approved` - Disetujui admin
- `rejected` - Ditolak admin
- `revision_requested` - Admin meminta revisi
- `scheduled` - Sudah dijadwalkan
- `done` - Selesai

### Badge untuk Status Revision:
- Warna: **Warning (Kuning)**
- Icon: `fas fa-edit`
- Text: "Perlu Revisi"

## Fitur Utama

### 1. Notifikasi Multi-Level
- **Dashboard**: Alert besar dengan catatan revisi dan tombol aksi
- **Halaman Aplikasi**: Badge status + preview catatan + tombol revisi
- **Halaman Index MBKM**: Alert + badge di tabel + tombol revisi menonjol
- **Halaman Edit**: Alert penuh dengan catatan revisi lengkap

### 2. Auto Status Management
- Status otomatis berubah ke `submitted` setelah mahasiswa submit revisi
- Revision notes otomatis dihapus setelah submit
- Tidak perlu intervensi manual

### 3. User Experience
- Mahasiswa tidak bisa melewatkan permintaan revisi
- Catatan revisi terlihat jelas di setiap tahap
- Tombol revisi lebih menonjol (warning color)
- Alert dapat di-dismiss untuk kenyamanan

## Testing Checklist

- [x] Migration berhasil dijalankan
- [x] Field baru ditambahkan ke model
- [x] Admin dapat request revision dengan catatan
- [x] Notifikasi muncul di dashboard mahasiswa
- [x] Notifikasi muncul di halaman aplikasi mahasiswa
- [x] Notifikasi muncul di halaman index MBKM
- [x] Catatan revisi lengkap muncul di halaman edit
- [x] Status berubah ke submitted setelah mahasiswa update
- [x] Revision notes dihapus setelah mahasiswa update
- [x] Badge status revision_requested muncul di semua lokasi
- [x] Tombol revisi lebih menonjol di halaman index

## File yang Dimodifikasi

### Database
- `database/migrations/2025_10_21_163401_add_admin_fields_to_mbkm_registrations_table.php` (NEW)

### Models
- `app/Models/MbkmRegistration.php`

### Controllers
- `app/Http/Controllers/Mahasiswa/DashboardController.php`
- `app/Http/Controllers/Frontend/MbkmRegistrationController.php`
- `app/Http/Controllers/Frontend/SkripsiRegistrationController.php`
- `app/Http/Controllers/Admin/MbkmRegistrationController.php`

### Views
- `resources/views/mahasiswa/dashboard.blade.php`
- `resources/views/mahasiswa/aplikasi.blade.php`
- `resources/views/frontend/mbkmRegistrations/index.blade.php`
- `resources/views/frontend/mbkmRegistrations/edit.blade.php`

### Routes
- `routes/web.php`

## Catatan Penting

1. **Revision Notes** hanya ditampilkan jika:
   - Status aplikasi adalah `revision_requested`
   - Field `revision_notes` tidak kosong

2. **Auto Clear**: Revision notes akan otomatis dihapus ketika mahasiswa submit ulang revisi

3. **Status Flow**: 
   - Admin request revision → status: `revision_requested`
   - Mahasiswa submit ulang → status: `submitted`
   - Admin review ulang → approve/reject/request revision lagi

4. **Implementasi untuk Skripsi**: Semua fitur revision juga berlaku untuk Skripsi Registration, bukan hanya MBKM

## Screenshot Lokasi

### 1. Dashboard Mahasiswa
- Alert warning di atas "Statistics Cards"
- Badge "Perlu Revisi" di bagian "Aplikasi Aktif"
- Badge "Perlu Revisi" di tabel "Aplikasi Terbaru"

### 2. Halaman Aplikasi Mahasiswa
- Badge "Perlu Revisi" di kolom status
- Catatan revisi (truncated) di kolom catatan
- Tombol "Revisi" warning di kolom aksi

### 3. Halaman Index MBKM
- Alert warning di atas tabel
- Badge + preview catatan di kolom status
- Tombol "Revisi" warning bold di kolom aksi

### 4. Halaman Edit MBKM
- Alert warning dengan catatan lengkap di atas form body


