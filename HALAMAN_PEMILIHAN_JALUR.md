# Halaman Pemilihan Jalur Skripsi

## Overview
Halaman pemilihan jalur skripsi yang memungkinkan mahasiswa memilih antara **Skripsi Reguler** atau **Skripsi MBKM** dengan tampilan yang modern dan informatif.

## Route
```
GET /choose-path
Route Name: frontend.choose-path
```

## Fitur Utama

### 1. **Desain Modern & Interaktif**
- Card-based layout dengan hover effects
- Gradient background menggunakan base color #22004C
- Animasi pulse pada header card
- Responsive design untuk mobile dan desktop

### 2. **Informasi Lengkap**
Setiap jalur menampilkan:
- Icon yang representatif
- Judul dan subtitle
- Badge status (Paling Populer / Program Khusus)
- Deskripsi singkat
- Daftar fitur/karakteristik
- Call-to-action button

### 3. **Jalur Skripsi Reguler**
**Karakteristik:**
- Penelitian individual dengan bimbingan dosen
- Topik penelitian akademik murni
- Proses: Pendaftaran → Seminar Proposal → Sidang
- Durasi standar 1-2 semester
- Fokus pada metodologi penelitian akademik

**Redirect:** `{{ route('frontend.skripsi-registrations.create') }}`

### 4. **Jalur Skripsi MBKM**
**Karakteristik:**
- Penelitian kolaboratif dalam kelompok
- Integrasi dengan pengalaman MBKM
- Proses: Pendaftaran Kelompok → Seminar → Sidang
- Memerlukan persetujuan kelompok dan dosen
- Fokus pada aplikasi praktis dan kolaborasi

**Redirect:** `{{ route('frontend.mbkm-registrations.create') }}`

### 5. **Info Box**
Memberikan panduan tambahan untuk mahasiswa yang masih ragu dalam memilih jalur, dengan saran untuk konsultasi dengan dosen pembimbing akademik.

## Integrasi dengan Dashboard

### Empty State
Ketika mahasiswa belum memiliki aplikasi aktif, dashboard akan menampilkan:
- Empty state dengan icon
- Pesan "Belum Ada Aplikasi Aktif"
- Button "Pilih Jalur Skripsi" yang mengarah ke halaman pemilihan

### Quick Start Guide
Panduan memulai di dashboard telah diupdate dengan:
- Langkah pertama: "Pilih jalur skripsi"
- Button "Mulai Sekarang" yang mengarah ke halaman pemilihan

## Styling

### Color Scheme
- **Primary Gradient:** `#22004C → #4A0080`
- **Background:** White cards dengan shadow
- **Text:** Dark gray (#2d3748, #4a5568, #718096)
- **Accent:** Blue untuk info box (#ebf8ff, #4299e1)
- **Badge Recommended:** Yellow (#fef3c7, #92400e)

### Interactive Elements
- **Hover Effect:** Card lift dengan shadow enhancement
- **Button Hover:** Slight lift dengan shadow
- **Icon Animation:** Arrow slides right on hover
- **Pulse Animation:** Subtle pulse pada card header

## User Flow

```
Dashboard (No Active Application)
    ↓
Click "Pilih Jalur Skripsi"
    ↓
Choose Path Page
    ↓
    ├─→ Skripsi Reguler → /skripsi-registrations/create
    └─→ Skripsi MBKM → /mbkm-registrations/create
```

## Responsive Design
- Desktop: 2 cards side by side (max-width: 1000px)
- Tablet/Mobile: Stacked cards (1 column)
- Minimum card width: 400px
- Adaptive padding and font sizes

## Accessibility
- Semantic HTML structure
- Clear visual hierarchy
- High contrast text
- Keyboard navigable (links)
- Screen reader friendly

## Future Enhancements
1. Add eligibility checker (cek syarat MBKM)
2. Add comparison table
3. Add video tutorial/walkthrough
4. Add FAQ section
5. Add testimonials from students
6. Add estimated completion time calculator

## Files Modified/Created
1. **Created:** `resources/views/frontend/choose-path.blade.php`
2. **Modified:** `routes/web.php` - Added route
3. **Modified:** `resources/views/frontend/home.blade.php` - Updated empty state and quick start guide
