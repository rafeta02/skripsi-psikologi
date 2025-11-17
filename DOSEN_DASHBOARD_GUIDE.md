# Panduan Dashboard Dosen

## Deskripsi
Dashboard Dosen adalah halaman khusus untuk dosen yang menyediakan akses ke berbagai fitur manajemen mahasiswa bimbingan, task assignments, dan penilaian.

## Struktur File

### Controller
- `app/Http/Controllers/Dosen/DashboardController.php` - Controller utama untuk dashboard dosen

### Routes
Routes dosen terdapat di `routes/web.php` dengan prefix `dosen` dan namespace `Dosen`:
```php
Route::group(['prefix' => 'dosen', 'as' => 'dosen.', 'namespace' => 'Dosen', 'middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/mahasiswa-bimbingan', 'DashboardController@mahasiswaBimbingan')->name('mahasiswa-bimbingan');
    Route::get('/task-assignments', 'DashboardController@taskAssignments')->name('task-assignments');
    Route::get('/scores', 'DashboardController@scores')->name('scores');
    Route::get('/profile', 'DashboardController@profile')->name('profile');
    Route::post('/assignments/{assignment}/respond', 'DashboardController@respondToAssignment')->name('assignments.respond');
});
```

### Views
- `resources/views/layouts/dosen.blade.php` - Layout khusus dosen
- `resources/views/partials/menu-dosen.blade.php` - Sidebar menu dosen
- `resources/views/dosen/dashboard.blade.php` - Dashboard utama
- `resources/views/dosen/mahasiswa-bimbingan.blade.php` - List mahasiswa bimbingan
- `resources/views/dosen/task-assignments.blade.php` - List task assignments
- `resources/views/dosen/scores.blade.php` - List scores yang diberikan
- `resources/views/dosen/profile.blade.php` - Profile dosen

## Menu Dashboard Dosen (5 Menu)

### 1. Dashboard
**URL:** `/dosen`

**Fitur:**
- Statistics cards menampilkan:
  - Total Mahasiswa Bimbingan
  - Total Task Pending
  - Total Task Completed
  - Total Scores Given
- Recent assignments (5 assignment terbaru)

### 2. Mahasiswa Bimbingan
**URL:** `/dosen/mahasiswa-bimbingan`

**Fitur:**
- Menampilkan list mahasiswa yang dibimbing (role = supervisor, status = accepted)
- Informasi mahasiswa:
  - NIM
  - Nama
  - Program Studi
  - Jenjang
  - Type aplikasi (Skripsi/MBKM)
  - Stage (Registration/Seminar/Defense)
  - Status aplikasi
  - Tanggal assignment
- Button untuk view detail aplikasi

### 3. Task Assignments
**URL:** `/dosen/task-assignments`

**Fitur:**
- Menampilkan semua task assignment yang diberikan admin
- Informasi assignment:
  - Mahasiswa (nama dan NIM)
  - Program Studi
  - Type dan Stage aplikasi
  - Role (Supervisor/Reviewer/Examiner)
  - Status (Assigned/Accepted/Rejected)
  - Tanggal assigned dan responded
  - Note
- Untuk assignment dengan status "Assigned":
  - Button "Respond" untuk accept/reject assignment
  - Modal form untuk memberikan response
- Untuk assignment yang sudah direspond:
  - Button "View" untuk melihat detail aplikasi

### 4. Application Scores
**URL:** `/dosen/scores`

**Fitur:**
- Menampilkan list semua score yang telah diberikan dosen
- Informasi score:
  - Mahasiswa (nama dan NIM)
  - Program Studi
  - Type dan Stage aplikasi
  - Score (nilai)
  - Note
  - Tanggal created
- Button view untuk melihat detail score

### 5. Profile
**URL:** `/dosen/profile`

**Fitur:**
- Menampilkan informasi lengkap dosen:
  - NIDN dan NIP
  - Data personal (tempat/tanggal lahir, gender)
  - Fakultas, Program Studi, Jenjang
  - Research Group
  - Bidang Keilmuan (multiple)
- Button edit profile

## Cara Menggunakan

### Akses Dashboard Dosen
1. Login sebagai dosen
2. Akses URL: `http://your-domain.com/dosen`
3. Dashboard akan menampilkan statistics dan recent assignments

### Merespond Task Assignment
1. Klik menu "Task Assignments"
2. Cari assignment dengan status "Assigned"
3. Klik button "Respond"
4. Pilih response (Accept/Reject)
5. (Opsional) Tambahkan note
6. Klik "Submit Response"

### Melihat Mahasiswa Bimbingan
1. Klik menu "Mahasiswa Bimbingan"
2. Lihat list mahasiswa yang dibimbing
3. Klik "View" untuk melihat detail aplikasi mahasiswa

### Melihat Scores
1. Klik menu "Application Scores"
2. Lihat list semua score yang telah diberikan
3. Klik "View" untuk melihat detail score

## Teknologi yang Digunakan
- Laravel (Backend framework)
- Blade Templates (View engine)
- AdminLTE 3 (UI framework)
- Bootstrap 4 (CSS framework)
- Font Awesome 5 (Icons)
- DataTables (untuk tabel data)

## Relasi Database

### ApplicationAssignment
- Menghubungkan Dosen dengan Application
- Field penting:
  - `lecturer_id` → foreign key ke table `dosens`
  - `application_id` → foreign key ke table `applications`
  - `role` → supervisor/reviewer/examiner
  - `status` → assigned/accepted/rejected

### ApplicationScore
- Menyimpan nilai yang diberikan dosen
- Field penting:
  - `examiner_id` → foreign key ke table `dosens`
  - `application_result_defence_id` → foreign key ke result defense
  - `score` → nilai
  - `note` → catatan

## Catatan Penting
1. Dashboard ini menggunakan middleware `auth`, pastikan user sudah login
2. Controller mengambil data dosen berdasarkan email user yang match dengan NIP atau NIDN
3. Jika tidak ada match, akan menggunakan dosen pertama sebagai fallback (untuk demo)
4. Untuk production, pastikan ada relasi yang jelas antara User dan Dosen

## Pengembangan Selanjutnya
1. Tambahkan fitur upload dokumen bimbingan
2. Tambahkan fitur chat/messaging dengan mahasiswa
3. Tambahkan notification untuk task assignment baru
4. Tambahkan calendar view untuk jadwal bimbingan
5. Tambahkan export data ke Excel/PDF
6. Tambahkan middleware khusus untuk role dosen
7. Tambahkan relasi User ke Dosen dengan field `user_id`
