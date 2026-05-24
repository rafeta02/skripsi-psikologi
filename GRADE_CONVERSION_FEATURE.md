# Fitur Konversi Nilai Angka ke Huruf - Hasil Sidang Skripsi

## Overview

Fitur ini menambahkan konversi otomatis nilai angka ke nilai huruf untuk hasil sidang skripsi mahasiswa berdasarkan standar penilaian yang telah ditentukan.

## Standar Konversi Nilai

| Rentang Nilai | Nilai Huruf | Keterangan |
|---------------|-------------|------------|
| ≥ 85          | A           | Sangat Baik |
| 80-84         | A-          | Sangat Baik |
| 75-79         | B+          | Baik |
| 70-74         | B           | Baik |
| 65-69         | C+          | Cukup |
| 60-64         | C           | Cukup |
| 55-59         | D           | Kurang |
| < 55          | E           | Sangat Kurang |

## File yang Dimodifikasi/Dibuat

### 1. Database Migration
**File:** `database/migrations/2025_12_15_000001_add_grade_to_application_result_defenses_table.php`

Menambahkan kolom baru:
- `final_grade_letter` (VARCHAR(2), nullable) - Menyimpan nilai huruf (A, A-, B+, B, C+, C, D, E)

### 2. Model ApplicationResultDefense
**File:** `app/Models/ApplicationResultDefense.php`

**Penambahan:**
- Field `final_grade_letter` di `$fillable`
- Method `convertScoreToGrade($score)` - Static method untuk konversi nilai angka ke huruf
- Method `getGradeDescription($grade)` - Static method untuk mendapatkan deskripsi nilai huruf
- Method `getFinalGradeLetterAttribute()` - Accessor untuk otomatis menghitung nilai huruf dari final_score

**Contoh Penggunaan:**
```php
// Konversi manual
$grade = ApplicationResultDefense::convertScoreToGrade(87); // Returns 'A'

// Deskripsi nilai
$description = ApplicationResultDefense::getGradeDescription('A'); // Returns 'Sangat Baik (≥ 85)'

// Otomatis dari model
$defense = ApplicationResultDefense::find(1);
$finalGrade = $defense->final_grade_letter; // Otomatis dihitung dari final_score
```

### 3. Observer ApplicationScore
**File:** `app/Observers/ApplicationScoreObserver.php`

Observer yang otomatis mengupdate nilai huruf ketika:
- ApplicationScore baru dibuat (created)
- ApplicationScore diupdate (updated)
- ApplicationScore dihapus (deleted)

**Cara Kerja:**
1. Ketika ada perubahan pada ApplicationScore
2. Observer menghitung ulang `final_score` dari ApplicationResultDefense terkait
3. Konversi `final_score` menjadi `final_grade_letter`
4. Update record ApplicationResultDefense dengan nilai baru

### 4. Service Provider
**File:** `app/Providers/AppServiceProvider.php`

Registrasi observer di method `boot()`:
```php
\App\Models\ApplicationScore::observe(\App\Observers\ApplicationScoreObserver::class);
```

### 5. Views - Print Score
**File:** `resources/views/frontend/applicationResultDefenses/print-score.blade.php`

**Penambahan:**
- Section "Nilai Huruf" di bagian Nilai Akhir Sidang
- Menampilkan nilai huruf dengan font besar (32pt)
- Menampilkan deskripsi nilai (contoh: "Sangat Baik (≥ 85)")

### 6. Views - Frontend Show
**File:** `resources/views/frontend/applicationResultDefenses/show.blade.php`

**Penambahan:**
- Card gradient yang menampilkan:
  - Nilai angka (48px font)
  - Nilai huruf (36px font dengan background)
  - Deskripsi nilai
  - Jumlah penguji

**Styling:**
- Background gradient: `linear-gradient(135deg, #667eea 0%, #764ba2 100%)`
- Warna teks: putih
- Border radius: 15px

### 7. Views - Admin Show
**File:** `resources/views/admin/applicationResultDefenses/show.blade.php`

**Penambahan:**
- Row "Nilai Akhir Sidang" - menampilkan nilai angka
- Row "Nilai Huruf" - menampilkan badge nilai huruf dengan deskripsi

### 8. Language File
**File:** `resources/lang/en/cruds.php`

**Penambahan:**
```php
'final_grade_letter'        => 'Final Grade Letter',
'final_grade_letter_helper' => ' ',
```

## Alur Kerja Sistem

### 1. Input Nilai oleh Penguji
```
Admin/Penguji → Input nilai komponen → ApplicationScore dibuat
```

### 2. Perhitungan Otomatis
```
ApplicationScore created/updated
    ↓
ApplicationScoreObserver triggered
    ↓
Hitung final_score (rata-rata semua penguji)
    ↓
Konversi ke final_grade_letter
    ↓
Update ApplicationResultDefense
```

### 3. Tampilan untuk Mahasiswa
```
Mahasiswa → View Hasil Sidang
    ↓
Lihat nilai angka + nilai huruf
    ↓
Print/Download transkrip nilai
```

## Contoh Perhitungan

### Skenario:
- **Penguji 1:** Nilai rata-rata = 85
- **Penguji 2:** Nilai rata-rata = 88
- **Penguji 3:** Nilai rata-rata = 82

### Perhitungan:
```
Final Score = (85 + 88 + 82) / 3 = 85
Final Grade Letter = A (karena 85 ≥ 85)
```

## Testing

### Manual Testing Steps:

1. **Test Konversi Nilai:**
```php
// Di tinker atau test
$grades = [
    85 => 'A',
    83 => 'A-',
    77 => 'B+',
    72 => 'B',
    67 => 'C+',
    62 => 'C',
    57 => 'D',
    50 => 'E'
];

foreach ($grades as $score => $expectedGrade) {
    $result = ApplicationResultDefense::convertScoreToGrade($score);
    echo "$score => $result (Expected: $expectedGrade)\n";
}
```

2. **Test Observer:**
```php
// Buat ApplicationScore baru
$score = ApplicationScore::create([
    'application_result_defence_id' => 1,
    'examiner_id' => 1,
    'penulisan' => 85,
    'isi' => 88,
    'analisis' => 82,
    // ... komponen lainnya
    'score' => 85
]);

// Cek apakah ApplicationResultDefense terupdate
$defense = ApplicationResultDefense::find(1);
echo "Final Score: " . $defense->final_score . "\n";
echo "Final Grade: " . $defense->final_grade_letter . "\n";
```

3. **Test Print View:**
- Login sebagai mahasiswa
- Buka halaman detail hasil sidang
- Klik tombol "Cetak Nilai"
- Verifikasi nilai huruf muncul dengan benar

## Migration

Untuk menerapkan perubahan database:

```bash
php artisan migrate
```

Jika sudah ada data existing:
```bash
# Update nilai huruf untuk data yang sudah ada
php artisan tinker

# Jalankan script berikut:
ApplicationResultDefense::whereNotNull('final_grade')
    ->chunk(100, function($defenses) {
        foreach($defenses as $defense) {
            $defense->update([
                'final_grade_letter' => ApplicationResultDefense::convertScoreToGrade($defense->final_score)
            ]);
        }
    });
```

## Rollback

Jika perlu rollback:

```bash
php artisan migrate:rollback --step=1
```

Kemudian hapus/comment registrasi observer di `AppServiceProvider.php`

## Catatan Penting

1. **Otomatis Update:** Nilai huruf akan otomatis terupdate setiap kali ada perubahan pada ApplicationScore
2. **Null Safety:** Jika belum ada nilai, sistem akan mengembalikan nilai default
3. **Performance:** Observer hanya trigger update pada record terkait, tidak mempengaruhi performa keseluruhan
4. **Konsistensi:** Standar konversi terpusat di model, memastikan konsistensi di seluruh aplikasi

## Future Enhancements

Potensi pengembangan di masa depan:
1. Konfigurasi standar nilai via admin panel
2. Export transkrip nilai ke PDF otomatis
3. Notifikasi email ke mahasiswa dengan nilai huruf
4. Dashboard analytics nilai per angkatan
5. Perbandingan nilai antar mahasiswa (percentile)

## Support

Untuk pertanyaan atau issue terkait fitur ini, silakan hubungi tim development.

