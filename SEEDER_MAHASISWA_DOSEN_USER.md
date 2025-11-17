# Seeder User Mahasiswa dan Dosen

## Deskripsi
Seeder ini membuat 5 user dengan role Mahasiswa dan 5 user dengan role Dosen berdasarkan data yang sudah ada di tabel `mahasiswas` dan `dosens`.

## File yang Dibuat
- `database/seeders/MahasiswaDosenUserSeeder.php`

## Cara Menjalankan

### Menjalankan Semua Seeder
```bash
php artisan db:seed
```

### Menjalankan Seeder Spesifik Saja
```bash
php artisan db:seed --class=MahasiswaDosenUserSeeder
```

### Reset Database dan Jalankan Semua Seeder
```bash
php artisan migrate:fresh --seed
```

## Data yang Dibuat

### 5 User Mahasiswa
Data diambil dari 5 mahasiswa pertama di tabel `mahasiswas`:

| Field | Format | Contoh |
|-------|--------|--------|
| name | Nama mahasiswa | Andi Pratama |
| email | nim@student.univ.ac.id | 2021010001@student.univ.ac.id |
| username | NIM | 2021010001 |
| password | password (hashed) | password |
| level | MAHASISWA | MAHASISWA |
| identity_number | NIM | 2021010001 |
| mahasiswa_id | ID dari tabel mahasiswas | 1 |
| no_hp | Auto-generated | 08000000001 |
| whatshapp | Auto-generated | 08000000001 |
| alamat | Dari data mahasiswa | Jl. Sudirman No. 123... |

### 5 User Dosen
Data diambil dari 5 dosen pertama di tabel `dosens`:

| Field | Format | Contoh |
|-------|--------|--------|
| name | Nama dosen | Dr. Ahmad Wijaya, M.Psi. |
| email | nip@lecturer.univ.ac.id | 197501012000031001@lecturer.univ.ac.id |
| username | NIP | 197501012000031001 |
| password | password (hashed) | password |
| level | DOSEN | DOSEN |
| identity_number | NIP | 197501012000031001 |
| dosen_id | ID dari tabel dosens | 1 |
| no_hp | Auto-generated | 08000000011 |
| whatshapp | Auto-generated | 08000000011 |
| alamat | NULL | - |

## Role yang Di-attach
Semua user (baik mahasiswa maupun dosen) akan mendapatkan role **User** (ID = 2).

## Catatan Penting

### Prerequisites
Seeder ini membutuhkan data dari seeder lain, pastikan urutan seeder sudah benar:
1. `RolesTableSeeder` - Membuat role User (ID = 2)
2. `DosenSeeder` - Membuat data dosen
3. `MahasiswaSeeder` - Membuat data mahasiswa
4. `MahasiswaDosenUserSeeder` - Membuat user untuk mahasiswa dan dosen

### Password Default
Semua user menggunakan password default: **password**

### Format Email
- Mahasiswa: `nim@student.univ.ac.id`
- Dosen: `nip@lecturer.univ.ac.id`

### Testing Login
Untuk testing, gunakan:
- **Email**: Sesuai format di atas
- **Password**: password

Contoh login mahasiswa:
- Email: `2021010001@student.univ.ac.id`
- Password: `password`

Contoh login dosen:
- Email: `197501012000031001@lecturer.univ.ac.id`
- Password: `password`

## Modifikasi

Jika ingin mengubah jumlah user yang dibuat, edit file `database/seeders/MahasiswaDosenUserSeeder.php`:

```php
// Ubah dari 5 menjadi jumlah yang diinginkan
$mahasiswas = Mahasiswa::take(5)->get(); // Ganti 5 dengan jumlah yang diinginkan
$dosens = Dosen::take(5)->get(); // Ganti 5 dengan jumlah yang diinginkan
```

Jika ingin mengubah format email atau password, edit bagian `User::create()` di dalam loop.

