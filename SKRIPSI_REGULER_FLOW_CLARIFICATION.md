# Klarifikasi Alur Skripsi Reguler - Review Proposal

## Perbedaan Utama dengan MBKM

### ❌ SKRIPSI REGULER - TIDAK ADA SEMINAR FORMAL
```
Pendaftaran → Bimbingan → Pendaftaran Reviewer → Admin Assign → 
Kirim Laporan → Review Individual → Laporan Hasil → Penelitian → Sidang
```

### ✅ MBKM - ADA SEMINAR FORMAL
```
Pendaftaran → Bimbingan → Pendaftaran Seminar → Admin Assign → 
Penjadwalan Seminar → Seminar Bersama → Laporan Hasil → Penelitian → Sidang
```

---

## Detail Alur Skripsi Reguler (Review Proposal)

### 1. Penyusunan Proposal
- **Peran**: Mahasiswa
- **Kegiatan**: Menyusun proposal dengan bimbingan dosen pembimbing
- **Output**: Draft proposal

### 2. Pendaftaran Reviewer (`SkripsiSeminar`)
- **Peran**: Mahasiswa
- **Model**: `SkripsiSeminar`
- **Dokumen**:
  - Proposal document (`proposal_document`)
  - Approval document dari pembimbing (`approval_document`)
  - Plagiarism check (`plagiarism_document`)
- **Verifikasi**: Admin review berkas
  - ✅ Approved → Lanjut assign reviewer
  - ❌ Rejected → Revisi dan upload ulang
- **Catatan**: Ini BUKAN pendaftaran seminar, hanya registrasi untuk mendapat reviewer

### 3. Penetapan Reviewer oleh Admin
- **Peran**: Admin
- **Model**: `ApplicationAssignment`
- **Data**:
  - `role` = `reviewer`
  - Biasanya 2 reviewer
  - `status` = `assigned` → reviewer dapat menerima/menolak
- **Output**: Reviewer ditugaskan ke proposal mahasiswa

### 4. Pengiriman Laporan ke Reviewer
- **Peran**: Mahasiswa
- **Metode**: 
  - Mahasiswa mengirimkan laporan proposal (yang sudah disetujui pembimbing) kepada para reviewer
  - TIDAK melalui system scheduling
  - TIDAK ada pertemuan seminar formal
- **Proses**: Reviewer menerima dan menilai secara individual

### 5. Review dan Penilaian (Individual)
- **Peran**: Reviewer (Dosen)
- **Proses**:
  - Setiap reviewer menilai proposal secara independent
  - Memberikan feedback/catatan
  - Menentukan hasil: Pass/Revision/Fail
- **Tidak Ada**:
  - ❌ Penjadwalan bersama (`ApplicationSchedule` dengan type `skripsi_seminar`)
  - ❌ Meeting/seminar formal
  - ❌ Presentasi proposal

### 6. Pelaporan Hasil Review
- **Peran**: Mahasiswa
- **Model**: `ApplicationResultSeminar`
- **Data**:
  - `result`: `passed` / `revision` / `failed`
  - `note`: Catatan dari reviewer
  - `revision_deadline`: Jika status revision
  - Dokumen pendukung:
    - `report_document`: Laporan hasil review
    - `attendance_document`: (opsional)
    - `form_document`: Form-form terkait
    - `latest_script`: Naskah proposal terbaru
    - `documentation`: Dokumentasi (jika ada)
- **Percabangan**:
  - ✅ `passed`: Lanjut ke proses penelitian
  - 🔄 `revision`: Revisi sesuai catatan reviewer
  - ❌ `failed`: Sesuai kebijakan akademik

---

## Model yang Digunakan

### 1. `SkripsiSeminar`
```php
- application_id
- reviewer_1_id  // Usulan mahasiswa (opsional)
- reviewer_2_id  // Usulan mahasiswa (opsional)
- title
- proposal_document
- approval_document  // Dari pembimbing
- plagiarism_document
```
**Fungsi**: Pendaftaran untuk mendapat reviewer (BUKAN pendaftaran seminar)

### 2. `ApplicationAssignment`
```php
- application_id
- dosen_id
- role = 'reviewer'
- status = 'assigned' / 'accepted' / 'rejected'
```
**Fungsi**: Admin assign reviewer ke mahasiswa

### 3. `ApplicationResultSeminar`
```php
- application_id
- result = 'passed' / 'revision' / 'failed'
- note
- revision_deadline
- report_document
- attendance_document
- form_document
- latest_script
- documentation
```
**Fungsi**: Mahasiswa melaporkan hasil review dari reviewer

---

## Apa yang TIDAK Digunakan untuk Skripsi Reguler

### ❌ `ApplicationSchedule` dengan `schedule_type` = `skripsi_seminar`
- Tidak ada penjadwalan seminar proposal
- Tidak ada meeting bersama reviewer
- Review dilakukan individual

### ❌ Seminar Formal
- Tidak ada presentasi proposal
- Tidak ada ruangan/waktu yang dijadwalkan
- Reviewer menilai secara terpisah

---

## Perbandingan dengan MBKM

| Aspek | Skripsi Reguler | MBKM |
|-------|----------------|------|
| **Pendaftaran** | `SkripsiSeminar` | `MbkmSeminar` |
| **Tujuan Pendaftaran** | Mendapat reviewer | Mendaftar seminar |
| **Penjadwalan** | ❌ Tidak ada | ✅ Ada (`ApplicationSchedule`) |
| **Format Review** | Individual | Seminar bersama |
| **Presentasi** | ❌ Tidak ada | ✅ Ada |
| **Hasil** | `ApplicationResultSeminar` | TBD (form tersendiri?) |

---

## Kesimpulan

Untuk **Skripsi Reguler**:
1. `SkripsiSeminar` hanya untuk registrasi reviewer, BUKAN seminar
2. Tidak ada scheduling (`ApplicationSchedule`)
3. Reviewer melakukan review secara individual
4. Tidak ada seminar/presentasi formal
5. Hasil dilaporkan via `ApplicationResultSeminar`

Untuk **MBKM**:
1. `MbkmSeminar` untuk pendaftaran seminar formal
2. Ada scheduling (`ApplicationSchedule`)
3. Seminar dilakukan bersama dengan presentasi
4. Ada pertemuan formal dengan reviewer

---

## Rekomendasi Penamaan

Untuk menghindari kebingungan, pertimbangkan rename:
- `SkripsiSeminar` → `SkripsiReviewerRegistration` atau `SkripsiProposalReview`
- Atau tambahkan keterangan di UI bahwa ini pendaftaran reviewer, bukan seminar

Atau minimal tambahkan dokumentasi/helper text yang jelas di form bahwa ini bukan pendaftaran seminar formal.
