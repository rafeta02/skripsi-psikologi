# 📅 Panduan Persetujuan Jadwal untuk Admin

## Akses Halaman Persetujuan

1. Login sebagai Admin
2. Buka menu **Jadwal** → **Application Schedules**
3. Atau akses langsung: `/admin/application-schedules`

## Tampilan Halaman

Halaman persetujuan jadwal memiliki **4 Tab** untuk memudahkan filtering:

### 📋 Tab 1: Menunggu Persetujuan
Menampilkan jadwal yang **perlu Anda review**:
- Status: Submitted atau Approved
- Tombol aksi: **Setujui ✅** dan **Tolak ❌**
- Ini adalah **tab prioritas** Anda

### ✅ Tab 2: Disetujui
Menampilkan jadwal yang **sudah Anda setujui**:
- Status: Scheduled
- Mahasiswa sudah bisa melanjutkan persiapan
- Hanya untuk referensi

### ❌ Tab 3: Ditolak
Menampilkan jadwal yang **ditolak**:
- Status: Rejected
- Menampilkan alasan penolakan
- Mahasiswa harus mengajukan ulang

### 📊 Tab 4: Semua Jadwal
Menampilkan **semua jadwal** tanpa filter:
- Untuk keperluan monitoring keseluruhan
- Bisa search dan filter manual

---

## Cara Menyetujui Jadwal

### Langkah-langkah:

1. **Buka tab "Menunggu Persetujuan"**
   - Lihat daftar jadwal yang perlu direview

2. **Review informasi jadwal:**
   - Nama mahasiswa & NIM
   - Tipe jadwal (Seminar/Sidang)
   - Waktu pelaksanaan
   - Tempat/Ruangan

3. **Klik tombol mata (👁️)** untuk melihat detail lengkap (opsional)
   - Dokumen persetujuan
   - Data mahasiswa
   - Catatan tambahan

4. **Klik tombol hijau (✅) "Setujui"**
   - Modal akan muncul

5. **Di Modal Persetujuan:**
   ```
   ┌─────────────────────────────────┐
   │  🟢 Setujui Jadwal              │
   ├─────────────────────────────────┤
   │  ℹ️ Jadwal seminar akan disetujui│
   │  dan mahasiswa dapat melanjutkan│
   │                                  │
   │  Catatan (Opsional):            │
   │  ┌───────────────────────────┐  │
   │  │                           │  │
   │  │ Contoh: "Jadwal sudah OK" │  │
   │  │                           │  │
   │  └───────────────────────────┘  │
   │                                  │
   │  [Batal]  [✅ Setujui]          │
   └─────────────────────────────────┘
   ```

6. **Tambahkan catatan** (opsional) untuk mahasiswa

7. **Klik "Setujui"**
   - Loading spinner muncul
   - Notifikasi sukses muncul
   - Data otomatis pindah ke tab "Disetujui"

### ✨ Hasil:
- ✅ Status berubah: `Scheduled`
- 📝 Catatan Anda tersimpan
- 📊 Action log tercatat
- 🔔 Mahasiswa dapat melihat jadwal disetujui

---

## Cara Menolak Jadwal

### Langkah-langkah:

1. **Buka tab "Menunggu Persetujuan"**

2. **Review jadwal** yang akan ditolak

3. **Klik tombol merah (❌) "Tolak"**
   - Modal akan muncul

4. **Di Modal Penolakan:**
   ```
   ┌─────────────────────────────────┐
   │  🔴 Tolak Jadwal                │
   ├─────────────────────────────────┤
   │  ⚠️ Pastikan Anda memberikan     │
   │  alasan yang jelas              │
   │                                  │
   │  Alasan Penolakan *:            │
   │  ┌───────────────────────────┐  │
   │  │ Contoh:                   │  │
   │  │ "Waktu bentrok dengan     │  │
   │  │  jadwal seminar lain.     │  │
   │  │  Mohon pilih waktu yang   │  │
   │  │  berbeda"                 │  │
   │  └───────────────────────────┘  │
   │  Minimal 10 karakter            │
   │                                  │
   │  [Batal]  [❌ Tolak]            │
   └─────────────────────────────────┘
   ```

5. **⚠️ WAJIB isi alasan penolakan**
   - Minimal 10 karakter
   - Jelaskan dengan jelas kenapa ditolak
   - Berikan solusi/saran jika memungkinkan

6. **Klik "Tolak"**
   - Validasi akan berjalan
   - Loading spinner muncul
   - Notifikasi sukses muncul
   - Data otomatis pindah ke tab "Ditolak"

### ✨ Hasil:
- ❌ Status berubah: `Rejected`
- 📝 Alasan penolakan tersimpan
- 📊 Action log tercatat
- 🔔 Mahasiswa melihat alasan & harus mengajukan ulang

---

## Contoh Alasan Penolakan yang Baik

### ✅ **Contoh BAIK:**

1. **Bentrok jadwal:**
   ```
   "Waktu yang dipilih bentrok dengan jadwal sidang mahasiswa lain. 
   Mohon pilih waktu alternatif: Senin 10:00 atau Rabu 14:00."
   ```

2. **Ruangan tidak tersedia:**
   ```
   "Ruang 301 sedang digunakan untuk acara fakultas pada tanggal tersebut. 
   Silakan pilih Ruang 302 atau 401 sebagai alternatif."
   ```

3. **Dokumen belum lengkap:**
   ```
   "Dokumen persetujuan pembimbing belum dilengkapi. 
   Mohon upload form persetujuan yang sudah ditandatangani kedua pembimbing."
   ```

4. **Waktu terlalu mepet:**
   ```
   "Jadwal sidang terlalu dekat dengan jadwal seminar (kurang dari 2 minggu). 
   Sesuai aturan, minimal jarak 1 bulan. Mohon reschedule."
   ```

### ❌ **Contoh BURUK (Hindari):**

1. ❌ "Ditolak" (Terlalu singkat, tidak informatif)
2. ❌ "Tidak bisa" (Tidak ada penjelasan)
3. ❌ "Salah" (Tidak spesifik)
4. ❌ "Coba lagi" (Tidak ada guidance)

---

## Tips & Best Practices

### 🎯 Efisiensi Kerja
- ✅ Cek tab "Menunggu Persetujuan" **setiap hari**
- ✅ Prioritaskan jadwal yang paling dekat waktunya
- ✅ Gunakan tombol "👁️ Lihat Detail" untuk review lengkap
- ✅ Manfaatkan search/filter di DataTable

### 📝 Komunikasi
- ✅ Berikan catatan yang **jelas dan konstruktif**
- ✅ Sertakan **alternatif solusi** saat menolak
- ✅ Gunakan bahasa yang **sopan dan profesional**
- ✅ Spesifik dalam memberikan alasan

### ⚡ Responsivitas
- ✅ Respon permohonan jadwal **maksimal 2 hari kerja**
- ✅ Prioritaskan jadwal yang mendekati deadline
- ✅ Koordinasi dengan admin lain untuk hindari duplikasi

### 🔍 Hal yang Perlu Dicek
1. **Kelengkapan Dokumen**
   - Form persetujuan pembimbing
   - Dokumen persyaratan lainnya

2. **Ketersediaan Ruangan**
   - Cek kalender ruangan
   - Pastikan tidak bentrok

3. **Ketersediaan Dosen Penguji**
   - Koordinasi dengan dosen terkait
   - Konfirmasi kehadiran

4. **Kesesuaian Waktu**
   - Cek dengan jadwal akademik
   - Hindari hari libur/acara fakultas

---

## Keyboard Shortcuts (Opsional)

Jika menggunakan desktop, bisa gunakan:
- **Tab** - Pindah antar field
- **Enter** - Submit form (di modal)
- **Esc** - Tutup modal
- **Ctrl + F** - Search di tabel

---

## Troubleshooting

### ❓ Masalah: Jadwal tidak muncul

**Solusi:**
1. Pastikan Anda di tab yang benar
2. Cek di tab "Semua Jadwal"
3. Gunakan fitur search
4. Refresh halaman (F5)

### ❓ Masalah: Tombol tidak berfungsi

**Solusi:**
1. Pastikan koneksi internet stabil
2. Refresh halaman
3. Clear browser cache
4. Coba browser lain

### ❓ Masalah: Error saat approve/reject

**Solusi:**
1. Refresh halaman untuk update CSRF token
2. Login ulang jika session expired
3. Hubungi IT support jika masalah berlanjut

### ❓ Masalah: Data tidak update setelah approve

**Solusi:**
1. Tunggu beberapa detik (auto-reload)
2. Manual refresh jika perlu
3. Cek di tab tujuan (Disetujui/Ditolak)

---

## Statistik & Monitoring

### Cek Kinerja Anda:
- Tab **Disetujui**: Lihat total jadwal yang sudah Anda approve
- Tab **Ditolak**: Review alasan penolakan untuk improvement
- Tab **Menunggu**: Monitor workload yang masih pending

### Monthly Review:
1. Total jadwal yang diproses
2. Rata-rata waktu response
3. Persentase approval vs rejection
4. Alasan penolakan terbanyak

---

## FAQ (Frequently Asked Questions)

### Q: Apakah bisa membatalkan approval?
**A:** Tidak otomatis. Jika perlu revisi, hubungi superadmin atau edit manual via halaman edit.

### Q: Berapa lama mahasiswa menunggu approval?
**A:** Maksimal 2 hari kerja. Usahakan lebih cepat untuk jadwal mendesak.

### Q: Apakah mahasiswa dapat notifikasi?
**A:** Ya, mahasiswa dapat melihat status di dashboard mereka. Email notification (planned feature).

### Q: Bisa approve banyak jadwal sekaligus?
**A:** Saat ini belum (one-by-one). Fitur bulk approve sedang dikembangkan.

### Q: Apa bedanya status "Approved" dan "Scheduled"?
**A:** 
- **Approved** = Dokumen disetujui, belum dijadwalkan
- **Scheduled** = Jadwal sudah fix dan disetujui

### Q: Bagaimana jika jadwal bentrok?
**A:** Sistem belum otomatis detect. Admin harus manual cek dan koordinasi.

---

## Kontak Support

Jika mengalami kendala teknis:
- 📧 Email: it-support@university.edu
- 💬 WhatsApp: +62-xxx-xxxx-xxxx
- 🏢 Datang langsung: Ruang IT Support (Gedung A Lt.1)

---

## Update Log

| Tanggal | Perubahan |
|---------|-----------|
| Okt 2025 | ✨ Initial release - Tab-based approval system |
| - | 🔜 Planned: Email notifications |
| - | 🔜 Planned: Bulk approve |
| - | 🔜 Planned: Calendar view |

---

**Terakhir diupdate:** Oktober 2025  
**Versi:** 1.0  

---

## Checklist Harian Admin

```
☐ Buka tab "Menunggu Persetujuan"
☐ Review semua jadwal pending
☐ Prioritaskan jadwal mendesak
☐ Approve/Reject dengan alasan jelas
☐ Koordinasi dengan dosen penguji
☐ Update status ruangan jika perlu
☐ Cek email/notifikasi mahasiswa
☐ Monitor tab "Disetujui" untuk follow-up
```

---

**Selamat bekerja! 💪**

