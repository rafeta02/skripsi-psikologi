## Pedoman Proses Aplikasi Skripsi

Panduan resmi dua alur proses: Skripsi Reguler dan Skripsi MBKM. Setiap langkah mencantumkan peran, form/tabel yang digunakan, dan keputusan yang mungkin terjadi.

### Terminologi Peran
- Mahasiswa: mengisi pendaftaran, unggah dokumen, pilih/jadwalkan seminar/sidang.
- Dosen Pembimbing/Reviewer/Penguji: validasi/menyetujui, memberi nilai.
- Admin: verifikasi berkas, setujui/tolak, assign dosen, kontrol status.

### Data Induk Penting
- `Mahasiswa`, `Dosen`, `Keilmuan`, `ResearchGroup`, `Ruang`, `Prodi`, `Faculty`, `Jenjang`, `User/Role/Permission`.

---

## Alur 1 — Skripsi Reguler

1) Pendaftaran Aplikasi Skripsi
- Peran: Mahasiswa
- Form: `Application` (type=`skripsi`, stage=`registration`)
- Output: Aplikasi tercatat dengan status awal (mis. `submitted`)

2) Pendaftaran Topik Skripsi
- Peran: Mahasiswa
- Form: `SkripsiRegistration` (judul, abstrak, lampiran KRS/KHS, dst.)
- Keputusan: Admin verifikasi berkas
  - Setujui: lanjut ke penugasan pembimbing
  - Tolak: revisi/unggah ulang `SkripsiRegistration`

3) Penugasan Dosen Pembimbing
- Peran: Admin
- Form: `ApplicationAssignment` (role=`supervisor`, status=`assigned`)
- Peran: Dosen
  - Aksi: setujui/tolak penugasan
  - Dampak: `ApplicationAssignment.status` → `accepted` / `rejected`

4) Penyusunan Proposal
- Peran: Mahasiswa
- Kegiatan: menyusun proposal (bukti lampiran digunakan saat pendaftaran seminar)

5) Pendaftaran Seminar Proposal
- Peran: Mahasiswa
- Form: `SkripsiSeminar` (lampiran: proposal, persetujuan, plagiasi)
- Keputusan: Admin verifikasi
  - Setujui: lanjut penetapan reviewer
  - Tolak: revisi/unggah ulang `SkripsiSeminar`

6) Penetapan Reviewer Seminar
- Peran: Admin
- Form: `ApplicationAssignment` tambahan (role=`reviewer`, tipikal 2 reviewer)

7) Penjadwalan Seminar Proposal
- Peran: Mahasiswa (koordinasi dengan 2 reviewer + 1 pembimbing)
- Form: `ApplicationSchedule` (schedule_type=`skripsi_seminar`, waktu, ruang/online)
- Keputusan: Admin verifikasi
  - Setujui: seminar dilaksanakan
  - Tolak: reschedule/unggah ulang `ApplicationSchedule`

8) Unggah Hasil Seminar Proposal
- Peran: Mahasiswa
- Form: `ApplicationResultSeminar` (hasil: `passed`/`revision`/`failed`, berita acara/dokumen terkait)
- Catatan: Jika `revision`, isi tenggat revisi. Jika `failed`, ikuti kebijakan akademik untuk pengulangan seminar.

9) Laporan Kendala (opsional, kapan saja)
- Peran: Mahasiswa
- Form: `ApplicationReport` (periodik/insidental; status `submitted`→`reviewed`)

10) Pendaftaran Sidang Skripsi
- Peran: Mahasiswa
- Form: `SkripsiDefense` (unggah seluruh persyaratan)
- Keputusan: Admin verifikasi
  - Setujui: lanjut penetapan penguji
  - Tolak: revisi/unggah ulang `SkripsiDefense`

11) Penetapan Dosen Penguji
- Peran: Admin
- Form: `ApplicationAssignment` (role=`examiner`, tipikal 2 penguji)

12) Penjadwalan Sidang Skripsi
- Peran: Mahasiswa
- Form: `ApplicationSchedule` (schedule_type=`skripsi_defense`, waktu, ruang/online)
- Keputusan: Admin verifikasi
  - Setujui: sidang dilaksanakan
  - Tolak: reschedule/unggah ulang `ApplicationSchedule`

13) Unggah Hasil Sidang Skripsi
- Peran: Mahasiswa
- Form: `ApplicationResultDefense` (hasil: `passed`/`revision`/`failed`, berita acara, naskah final, lampiran lengkap)
- Percabangan:
  - `failed`: ulang dari pendaftaran `SkripsiDefense`
  - `revision`/`passed`: lengkapi semua dokumen di `ApplicationResultDefense`

14) Penilaian Sidang
- Peran: Admin (trigger/assign dosen mengisi nilai), Dosen Penguji + Pembimbing
- Form: `ApplicationScore` (tautan ke `ApplicationResultDefense`, isi skor dan catatan)
- Output: Nilai akhir mahasiswa tersimpan

---

## Alur 2 — Skripsi MBKM

1) Pendaftaran MBKM
- Peran: Mahasiswa
- Form: `MbkmRegistration` (pilih `Dosen` pembimbing, `Keilmuan`, `ResearchGroup`, unggah KRS/KHS/SPP/proposal MBKM, dst.)
- Tambahan Anggota Kelompok (jika ada):
  - Form: `MbkmGroupMember` (role `ketua`/`anggota`)

2) Verifikasi Pendaftaran MBKM
- Peran: Admin
- Keputusan:
  - Setujui: otomatis buat `ApplicationAssignment` sesuai pilihan mahasiswa (role=`supervisor`, status=`assigned`)
  - Tolak (Revisi berkas): mahasiswa memperbaiki pada `MbkmRegistration` yang sama hingga memenuhi syarat
  - Tidak Memenuhi Syarat (ineligible): alur MBKM dihentikan; mahasiswa hanya boleh melanjutkan melalui alur Skripsi Reguler (`Application` + `SkripsiRegistration`)

3) Validasi Dosen Pembimbing MBKM
- Peran: Dosen
- Aksi: setujui/tolak penugasan
  - Tolak: `ApplicationAssignment.status`=`rejected`; mahasiswa revisi `MbkmRegistration` (pilih dosen lain)
  - Setujui: `ApplicationAssignment.status`=`accepted`; dosen menjadi pembimbing resmi

4) Pendaftaran Seminar MBKM
- Peran: Mahasiswa
- Form: `MbkmSeminar` (proposal/approval/plagiarism)
- Keputusan: Admin verifikasi
  - Setujui: lanjut penetapan reviewer
  - Tolak: revisi/unggah ulang `MbkmSeminar`

5) Penetapan Reviewer Seminar MBKM
- Peran: Admin
- Form: `ApplicationAssignment` (role=`reviewer`, tipikal 2 reviewer)

6) Penjadwalan Seminar MBKM
- Peran: Mahasiswa
- Form: `ApplicationSchedule` (schedule_type=`mbkm_seminar`)
- Keputusan: Admin verifikasi
  - Setujui: seminar dilaksanakan
  - Tolak: reschedule/unggah ulang `ApplicationSchedule`

7) Proses Skripsi Lanjutan
- Peran: Mahasiswa
- Kegiatan: mengerjakan skripsi sesuai judul/proposal MBKM
- Kendala (opsional): `ApplicationReport`

8) Pendaftaran Sidang Skripsi (akhir)
- Peran: Mahasiswa
- Form: `SkripsiDefense`
- Keputusan: Admin verifikasi
  - Setujui: penetapan penguji
  - Tolak: revisi/unggah ulang `SkripsiDefense`

9) Penetapan Penguji Sidang
- Peran: Admin
- Form: `ApplicationAssignment` (role=`examiner`, tipikal 2 penguji)

10) Penjadwalan Sidang Skripsi
- Peran: Mahasiswa
- Form: `ApplicationSchedule` (schedule_type=`skripsi_defense`)
- Keputusan: Admin verifikasi
  - Setujui: sidang dilaksanakan
  - Tolak: reschedule/unggah ulang `ApplicationSchedule`

11) Unggah Hasil Sidang Skripsi
- Peran: Mahasiswa
- Form: `ApplicationResultDefense`
- Percabangan:
  - `failed`: ulang dari `SkripsiDefense`
  - `passed`/`revision`: unggah dokumen lengkap di `ApplicationResultDefense`

12) Penilaian Akhir
- Peran: Admin (meng-assign dosen penguji), Dosen Penguji
- Form: `ApplicationScore`
- Output: Nilai akhir mahasiswa

---

## Kebijakan Verifikasi Pendaftaran MBKM

- Keputusan Admin pada `MbkmRegistration`:
  - Revisi berkas: pendaftaran MBKM tetap valid; mahasiswa boleh memperbaiki/unggah ulang berkas pada `MbkmRegistration` yang sama hingga memenuhi syarat. Langkah berikutnya diblokir sampai status disetujui.
  - Tidak memenuhi syarat (ineligible): mahasiswa tidak diperkenankan melanjutkan alur MBKM. Aplikasi MBKM ditutup, dan mahasiswa hanya boleh melanjutkan melalui alur Skripsi Reguler: `Application` (type=`skripsi`, stage=`registration`) → `SkripsiRegistration`.

- Implementasi yang disarankan:
  - Gunakan `status` dan `reason` pada `MbkmRegistration` untuk membedakan `revisi` vs `tidak_memenuhi_syarat`.
  - Jika `ineligible`: nonaktifkan pembuatan entitas MBKM lanjutan (`MbkmSeminar`, `ApplicationSchedule` dengan `schedule_type=mbkm_seminar`) dan tampilkan arahan alur Skripsi Reguler.
  - Jika `revisi`: izinkan edit/unggah ulang pada `MbkmRegistration`; blokir langkah berikutnya sampai status berubah menjadi disetujui.

---

## Ringkasan Status Umum
- Alur status lazim: `submitted` → `approved`/`rejected` → `scheduled` → `done` (bergantung tahap).
- Revisi/unggah ulang dilakukan pada form yang relevan: `SkripsiRegistration`, `MbkmRegistration`, `SkripsiSeminar`, `MbkmSeminar`, `ApplicationSchedule`, `SkripsiDefense`, `ApplicationResultDefense`.
- Penugasan peran dosen terpusat di `ApplicationAssignment` dengan `role` (`supervisor`/`reviewer`/`examiner`) dan `status` (`assigned`/`accepted`/`rejected`).
- Penjadwalan terpusat di `ApplicationSchedule` dengan `schedule_type` sesuai tahap.


