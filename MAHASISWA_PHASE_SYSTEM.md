# Sistem Fase Dashboard Mahasiswa

## 📋 Overview

Dashboard Mahasiswa menggunakan **sistem fase** untuk menampilkan menu dan fitur secara dinamis berdasarkan progress skripsi mahasiswa. Menu yang ditampilkan menyesuaikan dengan tahap yang sudah dicapai.

---

## 🎯 5 Fase Progress Skripsi

### **Fase 0: Belum Mendaftar**
**Status:** Belum ada aplikasi skripsi

**Menu yang Tampil:**
- ✅ Dashboard
- ✅ Profile

**Fitur:**
- Hanya bisa akses dashboard dan profile
- Dashboard menampilkan tombol "Mulai Daftar"
- Progress bar: 0%

**Next Step:** Pilih jalur skripsi (Skripsi Reguler/MBKM) dan lakukan pendaftaran

---

### **Fase 1: Sudah Pendaftaran**
**Status:** Sudah melakukan pendaftaran (SkripsiRegistration/MbkmRegistration exists)

**Menu yang Tampil:**
- ✅ Dashboard
- ✅ **Aplikasi Saya** (NEW)
- ✅ **Dokumen** (NEW)
- ✅ Profile

**Fitur:**
- Bisa lihat dan manage aplikasi
- Bisa upload dokumen pendaftaran
- Dashboard menampilkan tombol "Daftar Seminar"
- Progress bar: 25%

**Next Step:** Tunggu persetujuan admin dan daftar seminar proposal

---

### **Fase 2: Sudah Seminar**
**Status:** Sudah melakukan seminar (SkripsiSeminar/MbkmSeminar exists)

**Menu yang Tampil:**
- ✅ Dashboard
- ✅ Aplikasi Saya
- ✅ **Bimbingan** (NEW)
- ✅ **Jadwal** (NEW)
- ✅ Dokumen
- ✅ Profile

**Fitur:**
- Bisa lihat dosen pembimbing
- Bisa lihat jadwal seminar/bimbingan
- Dashboard menampilkan tombol "Daftar Sidang"
- Progress bar: 50%

**Next Step:** Lakukan perbaikan berdasarkan masukan dan daftar sidang skripsi

---

### **Fase 3: Sudah Sidang**
**Status:** Sudah melakukan sidang (SkripsiDefense exists)

**Menu yang Tampil:**
- ✅ Dashboard
- ✅ Aplikasi Saya
- ✅ Bimbingan
- ✅ Jadwal
- ✅ Dokumen
- ✅ Profile

**Fitur:**
- Semua menu sudah terbuka
- Bisa lihat jadwal sidang
- Progress bar: 75%

**Next Step:** Tunggu hasil penilaian dari dosen penguji

---

### **Fase 4: Nilai Tersedia**
**Status:** Nilai sudah tersedia (ApplicationScore exists)

**Menu yang Tampil:**
- ✅ Dashboard
- ✅ Aplikasi Saya
- ✅ Bimbingan
- ✅ Jadwal
- ✅ Dokumen
- ✅ Profile

**Fitur:**
- Semua menu terbuka
- Bisa lihat nilai sidang
- Progress bar: 100% ✨
- Status: **SELESAI**

**Next Step:** Proses selesai, tunggu kelulusan

---

## 🔧 Implementasi Teknis

### Fungsi `determinePhase()` di Controller

```php
private function determinePhase($mahasiswa)
{
    // Check from highest phase to lowest
    // Phase 4: Check if scores are available
    // Phase 3: Check if defense is done
    // Phase 2: Check if seminar is done
    // Phase 1: Check if registration exists
    // Phase 0: No application
}
```

### Logic Pengecekan Fase:

1. **Phase 4:** ApplicationScore exists → Nilai tersedia
2. **Phase 3:** SkripsiDefense/MbkmDefense exists → Sudah sidang
3. **Phase 2:** SkripsiSeminar/MbkmSeminar exists → Sudah seminar
4. **Phase 1:** SkripsiRegistration/MbkmRegistration exists → Sudah pendaftaran
5. **Phase 0:** No application atau application tanpa registration

### Conditional Menu Display:

```blade
{{-- Dashboard - Always visible --}}
<li>Dashboard</li>

{{-- Phase 1+ --}}
@if($currentPhase >= 1)
    <li>Aplikasi Saya</li>
    <li>Dokumen</li>
@endif

{{-- Phase 2+ --}}
@if($currentPhase >= 2)
    <li>Bimbingan</li>
    <li>Jadwal</li>
@endif

{{-- Profile - Always visible --}}
<li>Profile</li>
```

---

## 📊 Progress Indicator

Dashboard menampilkan **visual progress indicator** dengan:

### 1. Progress Bar
- Menampilkan persentase progress: (currentPhase / 4) * 100%
- Warna: Success green
- Height: 30px dengan text percentage

### 2. Phase Steps (4 Steps)
Visual circles untuk 4 tahap utama:
- **Pendaftaran** (Phase 1)
- **Seminar** (Phase 2)
- **Sidang** (Phase 3)
- **Nilai** (Phase 4)

**States:**
- **Inactive:** Abu-abu, opacity 0.5
- **Current:** Kuning, pulse animation
- **Completed:** Hijau, checkmark icon

### 3. Phase Information Card
Menampilkan:
- **Phase Name:** Nama fase saat ini
- **Phase Description:** Deskripsi status
- **Next Step:** Langkah selanjutnya yang harus dilakukan
- **Action Button:** Tombol CTA sesuai fase

---

## 🎨 Visual Design

### Color Scheme:
- **Primary:** Purple gradient (#22004C → #4A0080)
- **Success:** Green (#28a745)
- **Warning:** Yellow (#ffc107)
- **Info:** Blue (default Bootstrap)

### Animations:
```css
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
```

Current phase icon beranimasi pulse untuk menarik perhatian.

---

## 🔄 Flow Diagram

```
START
  ↓
Fase 0: Belum Mendaftar
  ↓ (Pilih jalur & daftar)
Fase 1: Sudah Pendaftaran
  ↓ (Daftar & lakukan seminar)
Fase 2: Sudah Seminar
  ↓ (Daftar & lakukan sidang)
Fase 3: Sudah Sidang
  ↓ (Tunggu nilai)
Fase 4: Nilai Tersedia
  ↓
SELESAI ✅
```

---

## 📱 Menu Visibility Matrix

| Menu | Fase 0 | Fase 1 | Fase 2 | Fase 3 | Fase 4 |
|------|--------|--------|--------|--------|--------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Aplikasi Saya | ❌ | ✅ | ✅ | ✅ | ✅ |
| Bimbingan | ❌ | ❌ | ✅ | ✅ | ✅ |
| Jadwal | ❌ | ❌ | ✅ | ✅ | ✅ |
| Dokumen | ❌ | ✅ | ✅ | ✅ | ✅ |
| Profile | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 🚀 Action Buttons per Fase

### Fase 0:
```html
<a href="{{ route('frontend.choose-path') }}">
    <i class="fas fa-route"></i> Mulai Daftar
</a>
```

### Fase 1:
```html
<!-- For Skripsi -->
<a href="{{ route('frontend.skripsi-seminars.index') }}">
    <i class="fas fa-presentation"></i> Daftar Seminar
</a>

<!-- For MBKM -->
<a href="{{ route('frontend.mbkm-seminars.index') }}">
    <i class="fas fa-presentation"></i> Daftar Seminar
</a>
```

### Fase 2:
```html
<a href="{{ route('frontend.skripsi-defenses.index') }}">
    <i class="fas fa-graduation-cap"></i> Daftar Sidang
</a>
```

### Fase 3 & 4:
No action button (waiting for results/completion)

---

## 🔍 Database Checks

### Models Checked:
1. **Application** - Base application
2. **SkripsiRegistration / MbkmRegistration** - Phase 1
3. **SkripsiSeminar / MbkmSeminar** - Phase 2
4. **SkripsiDefense** - Phase 3
5. **ApplicationResultDefense & ApplicationScore** - Phase 4

### Query Example:
```php
// Check Phase 2
$seminar = SkripsiSeminar::where('application_id', $activeApplication->id)->first();
if ($seminar) {
    // Phase 2 confirmed
}
```

---

## 💡 Best Practices

### 1. Always Pass $currentPhase
Setiap method di controller harus pass `$currentPhase` ke view:
```php
$phaseData = $this->determinePhase($mahasiswa);
$currentPhase = $phaseData['phase'];
return view('mahasiswa.dashboard', compact('currentPhase', ...));
```

### 2. Fallback for Missing Phase
Jika `$currentPhase` tidak ada, menu default ke fase 0:
```blade
@if(isset($currentPhase) && $currentPhase >= 1)
    {{-- Show menu --}}
@endif
```

### 3. Update Phase Logic
Jika ada perubahan alur, update di `determinePhase()` method.

### 4. Consistent Naming
- Phase 0: Belum Mendaftar
- Phase 1: Sudah Pendaftaran
- Phase 2: Sudah Seminar
- Phase 3: Sudah Sidang
- Phase 4: Nilai Tersedia

---

## 🧪 Testing Scenarios

### Test Fase 0:
1. Login sebagai mahasiswa baru
2. Belum punya aplikasi
3. Cek: Hanya Dashboard & Profile muncul
4. Cek: Progress bar 0%
5. Cek: Tombol "Mulai Daftar" ada

### Test Fase 1:
1. Buat aplikasi + registration
2. Cek: Menu Aplikasi & Dokumen muncul
3. Cek: Progress bar 25%
4. Cek: Tombol "Daftar Seminar" ada

### Test Fase 2:
1. Tambah seminar data
2. Cek: Menu Bimbingan & Jadwal muncul
3. Cek: Progress bar 50%
4. Cek: Tombol "Daftar Sidang" ada

### Test Fase 3:
1. Tambah defense data
2. Cek: Semua menu muncul
3. Cek: Progress bar 75%
4. Cek: Info "tunggu nilai"

### Test Fase 4:
1. Tambah score data
2. Cek: Progress bar 100%
3. Cek: Status "Selesai"

---

## 🎯 User Benefits

1. **Clear Progress Tracking:** Mahasiswa tahu posisi mereka di mana
2. **Guided Navigation:** Hanya menu yang relevan yang muncul
3. **Next Step Clarity:** Selalu tahu apa yang harus dilakukan selanjutnya
4. **Visual Feedback:** Progress bar dan icons memberikan feedback visual
5. **Reduced Confusion:** Tidak overwhelm dengan menu yang belum diperlukan

---

## 📝 Notes

- Sistem fase ini **client-side conditional rendering** berdasarkan data dari server
- Fase dihitung ulang setiap page load untuk akurasi
- Compatible dengan Skripsi Reguler dan MBKM
- Progress percentage: Linear (0%, 25%, 50%, 75%, 100%)
- Fase bisa di-customize sesuai kebutuhan institusi

---

## 🔗 Related Files

- `app/Http/Controllers/Mahasiswa/DashboardController.php` - Phase logic
- `resources/views/layouts/mahasiswa.blade.php` - Dynamic menu
- `resources/views/mahasiswa/dashboard.blade.php` - Progress indicator
- `MAHASISWA_DASHBOARD_GUIDE.md` - General dashboard guide

---

**Sistem fase ini memastikan mahasiswa mendapat pengalaman yang guided dan tidak overwhelmed dengan terlalu banyak opsi di awal!** 🎓✨
