# Panduan Dashboard Mahasiswa

## Deskripsi
Dashboard Mahasiswa adalah halaman khusus untuk mahasiswa yang menyediakan akses ke berbagai fitur manajemen aplikasi skripsi, bimbingan, jadwal, dan dokumen dengan menggunakan **top navigation menu**.

## Struktur File

### Controller
- `app/Http/Controllers/Mahasiswa/DashboardController.php` - Controller utama untuk dashboard mahasiswa

### Routes
Routes mahasiswa terdapat di `routes/web.php` dengan prefix `mahasiswa` dan namespace `Mahasiswa`:
```php
Route::group(['prefix' => 'mahasiswa', 'as' => 'mahasiswa.', 'namespace' => 'Mahasiswa', 'middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/aplikasi', 'DashboardController@aplikasi')->name('aplikasi');
    Route::get('/bimbingan', 'DashboardController@bimbingan')->name('bimbingan');
    Route::get('/jadwal', 'DashboardController@jadwal')->name('jadwal');
    Route::get('/dokumen', 'DashboardController@dokumen')->name('dokumen');
    Route::get('/profile', 'DashboardController@profile')->name('profile');
});
```

### Views
- `resources/views/layouts/mahasiswa.blade.php` - Layout khusus mahasiswa dengan top navbar
- `resources/views/mahasiswa/dashboard.blade.php` - Dashboard utama
- `resources/views/mahasiswa/aplikasi.blade.php` - List aplikasi
- `resources/views/mahasiswa/bimbingan.blade.php` - Informasi bimbingan
- `resources/views/mahasiswa/jadwal.blade.php` - Jadwal seminar/sidang
- `resources/views/mahasiswa/dokumen.blade.php` - Kelola dokumen
- `resources/views/mahasiswa/profile.blade.php` - Profile mahasiswa

## 🎯 Sistem Fase (Phase System)

Dashboard mahasiswa menggunakan **sistem fase dinamis** yang menampilkan menu berdasarkan progress skripsi:

### 5 Fase Progress:
1. **Fase 0:** Belum Mendaftar - Menu: Dashboard, Profile
2. **Fase 1:** Sudah Pendaftaran - Menu: Dashboard, Aplikasi, Dokumen, Profile
3. **Fase 2:** Sudah Seminar - Menu: Dashboard, Aplikasi, Bimbingan, Jadwal, Dokumen, Profile
4. **Fase 3:** Sudah Sidang - Menu: Semua menu
5. **Fase 4:** Nilai Tersedia - Menu: Semua menu (Progress 100%)

> 📖 **Lihat detail lengkap di:** [MAHASISWA_PHASE_SYSTEM.md](MAHASISWA_PHASE_SYSTEM.md)

---

## Menu Dashboard Mahasiswa (6 Menu di Top Navbar)

### 1. Dashboard
**URL:** `/mahasiswa`

**Fitur:**
- Statistics cards menampilkan:
  - Total Aplikasi
  - Aplikasi Aktif
  - Aplikasi Selesai
- Informasi aplikasi aktif
- Daftar dosen pembimbing & penguji
- Jadwal terbaru (3 jadwal terakhir)
- Recent applications (5 aplikasi terbaru)
- Quick action untuk buat aplikasi baru

**Screenshots Fitur:**
- 3 small boxes dengan statistik
- Card aplikasi aktif dengan detail
- Card jadwal upcoming di sidebar
- Table aplikasi terbaru

### 2. Aplikasi Saya
**URL:** `/mahasiswa/aplikasi`

**Fitur:**
- List semua aplikasi yang pernah dibuat mahasiswa
- Informasi untuk setiap aplikasi:
  - Jenis (Skripsi/MBKM)
  - Tahap (Registration/Seminar/Defense)
  - Status (Submitted/Approved/Rejected/Scheduled/Done)
  - Tanggal daftar
  - Notes
- Button untuk view detail aplikasi
- Button untuk buat aplikasi baru
- Empty state jika belum ada aplikasi

### 3. Bimbingan
**URL:** `/mahasiswa/bimbingan`

**Fitur:**
- Menampilkan informasi dosen pembimbing untuk setiap aplikasi
- Grouped by aplikasi (Skripsi/MBKM)
- Informasi untuk setiap dosen pembimbing:
  - Nama dosen
  - NIDN
  - Status assignment (Assigned/Accepted/Rejected)
  - Note dari dosen (jika ada)
- Visual card untuk setiap dosen dengan icon
- Link ke detail aplikasi
- Empty state jika belum ada bimbingan

### 4. Jadwal
**URL:** `/mahasiswa/jadwal`

**Fitur:**
- Menampilkan semua jadwal seminar, sidang, dan bimbingan
- Informasi untuk setiap jadwal:
  - Jenis aplikasi (Skripsi/MBKM)
  - Schedule type (Seminar/Defense/Consultation)
  - Waktu
  - Tempat (Ruang fisik atau Online)
  - Link meeting (jika online)
  - Catatan/note
- Card design dengan gradient header
- Empty state jika belum ada jadwal

### 5. Dokumen
**URL:** `/mahasiswa/dokumen`

**Fitur:**
- Menampilkan dokumen grouped by aplikasi
- Quick links untuk upload dokumen:
  - Dokumen Pendaftaran (Registration)
  - Dokumen Seminar
  - Dokumen Sidang
- Link ke detail aplikasi untuk manage dokumen
- Alert informasi cara upload dokumen
- Button actions berdasarkan stage aplikasi
- Empty state jika belum ada dokumen

### 6. Profile
**URL:** `/mahasiswa/profile`

**Fitur:**
- Menampilkan informasi lengkap mahasiswa:
  - **Informasi Personal:**
    - NIM
    - Nama lengkap
    - Tempat & tanggal lahir
    - Gender
    - Kelas
    - Alamat
  - **Informasi Akademik:**
    - Fakultas
    - Program Studi
    - Jenjang
    - Tahun Masuk
- Photo profile placeholder
- Button edit profile
- Layout 2 kolom (Photo di kiri, Info di kanan)

## Perbedaan dengan Dashboard Dosen

### Dashboard Mahasiswa:
- ✅ **Top Navigation** (navbar di atas)
- ✅ Menu horizontal di navbar
- ✅ Full width container
- ✅ Focus pada aplikasi dan bimbingan mahasiswa
- ✅ Layout menggunakan `hold-transition layout-top-nav`

### Dashboard Dosen:
- ❌ **Sidebar Navigation** (sidebar di samping)
- ❌ Menu vertical di sidebar
- ❌ Content dengan sidebar
- ❌ Focus pada task assignment dan scoring
- ❌ Layout menggunakan `sidebar-mini layout-fixed`

## Cara Menggunakan

### Akses Dashboard Mahasiswa
1. Login sebagai mahasiswa
2. Akses URL: `http://your-domain.com/mahasiswa`
3. Dashboard akan menampilkan statistics dan aplikasi aktif

### Membuat Aplikasi Baru
1. Dari dashboard, klik "Buat Aplikasi Baru" atau
2. Klik menu "Aplikasi Saya" → "Buat Aplikasi Baru"
3. Sistem akan redirect ke halaman choose-path

### Melihat Bimbingan
1. Klik menu "Bimbingan" di navbar
2. Lihat daftar dosen pembimbing untuk setiap aplikasi
3. Klik "Lihat Detail Aplikasi" untuk info lebih lanjut

### Mengecek Jadwal
1. Klik menu "Jadwal" di navbar
2. Lihat semua jadwal seminar/sidang
3. Klik "Join Meeting" jika jadwal online

### Upload Dokumen
1. Klik menu "Dokumen" di navbar
2. Pilih aplikasi yang ingin di-upload dokumennya
3. Klik quick link sesuai tahap (Registration/Seminar/Defense)
4. Upload dokumen di halaman yang sesuai

## Teknologi yang Digunakan
- Laravel (Backend framework)
- Blade Templates (View engine)
- AdminLTE 3 dengan layout top-nav (UI framework)
- Bootstrap 4 (CSS framework)
- Font Awesome 5 (Icons)
- Custom gradient purple theme (#22004C)

## Relasi Database

### Application
- Primary data untuk aplikasi skripsi/MBKM
- Field penting:
  - `mahasiswa_id` → foreign key ke table `mahasiswas`
  - `type` → skripsi/mbkm
  - `stage` → registration/seminar/defense
  - `status` → submitted/approved/rejected/scheduled/done

### ApplicationAssignment
- Relasi dosen dengan aplikasi mahasiswa
- Field penting:
  - `application_id` → foreign key ke `applications`
  - `lecturer_id` → foreign key ke `dosens`
  - `role` → supervisor/reviewer/examiner
  - `status` → assigned/accepted/rejected

### ApplicationSchedule
- Jadwal untuk aplikasi
- Field penting:
  - `application_id` → foreign key ke `applications`
  - `ruang_id` → foreign key ke `ruangs` (optional)
  - `waktu` → datetime jadwal
  - `schedule_type` → jenis jadwal
  - `online_meeting` → link meeting online
  - `custom_place` → tempat custom

## Navigation Menu Structure

### Top Navbar Items:
```
[Logo] | Dashboard | Aplikasi Saya | Bimbingan | Jadwal | Dokumen | Profile | [User Dropdown]
```

### User Dropdown:
- Profile
- Sign out

## Color Scheme
- Primary Color: `#22004C` (Purple Dark)
- Secondary Color: `#4A0080` (Purple)
- Gradient: `linear-gradient(135deg, #22004C 0%, #4A0080 100%)`
- Success: Bootstrap success
- Warning: Bootstrap warning
- Info: Bootstrap info

## Responsive Design
- Navbar collapsible di mobile
- Cards responsive dengan Bootstrap grid
- Table responsive dengan `table-responsive` class
- Mobile-friendly buttons dan forms

## Security & Access Control
- Middleware `auth` untuk semua routes
- Check mahasiswa profile existence
- Redirect to profile creation if not exists
- Data isolation per mahasiswa (only show own data)

## Catatan Penting
1. Dashboard ini menggunakan middleware `auth`, pastikan user sudah login
2. Controller mengambil data mahasiswa berdasarkan `user->mahasiswa`
3. Jika mahasiswa belum create profile, akan redirect ke create profile
4. Semua data ter-filter berdasarkan `mahasiswa_id` untuk keamanan
5. Layout menggunakan top navigation, berbeda dengan dashboard dosen yang sidebar

## Pengembangan Selanjutnya
1. Tambahkan fitur notification untuk jadwal baru
2. Tambahkan fitur chat dengan dosen pembimbing
3. Tambahkan calendar view untuk jadwal
4. Tambahkan progress tracker visual (timeline)
5. Tambahkan reminder otomatis untuk jadwal
6. Tambahkan download certificate setelah lulus
7. Tambahkan rating/feedback untuk dosen pembimbing
8. Integrate dengan sistem email notification
