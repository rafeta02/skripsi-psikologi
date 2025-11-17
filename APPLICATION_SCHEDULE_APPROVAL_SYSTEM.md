# Sistem Persetujuan Jadwal Seminar/Sidang

## Overview
Sistem persetujuan jadwal seminar/sidang yang memungkinkan admin untuk meninjau, menyetujui, atau menolak jadwal yang diajukan oleh mahasiswa.

## Fitur Utama

### 1. Halaman Persetujuan dengan Tab Filter
Halaman `admin/application-schedules` sekarang memiliki 4 tab untuk memudahkan admin:

- **Menunggu Persetujuan** - Jadwal dengan status `submitted` atau `approved` yang belum dijadwalkan
- **Disetujui** - Jadwal yang sudah disetujui dengan status `scheduled`
- **Ditolak** - Jadwal yang ditolak dengan status `rejected`
- **Semua Jadwal** - Menampilkan semua jadwal tanpa filter

### 2. Quick Actions
Di tab "Menunggu Persetujuan", admin dapat langsung:
- ✅ **Menyetujui** jadwal dengan tombol hijau
- ❌ **Menolak** jadwal dengan tombol merah
- 👁️ **Melihat detail** lengkap jadwal

### 3. Modal Persetujuan
#### Modal Setujui
- Muncul ketika admin klik tombol setujui
- Admin dapat menambahkan catatan (opsional)
- Setelah disetujui, status application berubah menjadi `scheduled`
- Action log tercatat otomatis

#### Modal Tolak
- Muncul ketika admin klik tombol tolak
- Admin **wajib** memberikan alasan penolakan (minimal 10 karakter)
- Alasan penolakan disimpan di field `notes` pada Application
- Action log tercatat otomatis

## Flow Persetujuan Jadwal

### Skenario 1: Jadwal Disetujui
```
1. Mahasiswa mengajukan jadwal → Status: submitted/approved
2. Admin melihat di tab "Menunggu Persetujuan"
3. Admin klik tombol ✅ Setujui
4. Muncul modal konfirmasi
5. Admin (opsional) menambahkan catatan
6. Klik "Setujui"
7. Status berubah → scheduled
8. ApplicationAction tercatat: "schedule_approved"
9. Jadwal pindah ke tab "Disetujui"
10. DataTable auto-reload
```

### Skenario 2: Jadwal Ditolak
```
1. Mahasiswa mengajukan jadwal → Status: submitted/approved
2. Admin melihat di tab "Menunggu Persetujuan"
3. Admin klik tombol ❌ Tolak
4. Muncul modal konfirmasi
5. Admin WAJIB memberikan alasan penolakan
6. Klik "Tolak"
7. Alasan disimpan ke application.notes
8. ApplicationAction tercatat: "schedule_rejected"
9. Jadwal pindah ke tab "Ditolak"
10. DataTable auto-reload
```

## Teknologi yang Digunakan

### Frontend
- **Bootstrap Tabs** - Untuk tab navigation
- **DataTables** - Untuk tabel dengan server-side processing
- **SweetAlert2** - Untuk notifikasi sukses/error
- **Bootstrap Modals** - Untuk form approve/reject
- **jQuery Ajax** - Untuk komunikasi dengan backend

### Backend
- **Yajra DataTables** - Server-side processing
- **Laravel Routes** - POST routes untuk approve/reject
- **Database Transactions** - Memastikan data consistency
- **ApplicationAction Model** - Untuk audit trail

## File yang Terlibat

### Views
- `resources/views/admin/applicationSchedules/index.blade.php` - Halaman utama dengan tabs dan modals
- `resources/views/admin/applicationSchedules/show.blade.php` - Detail jadwal dengan approve/reject

### Controller
- `app/Http/Controllers/Admin/ApplicationScheduleController.php`
  - Method `index()` - Handle DataTables dengan filtering
  - Method `approve()` - Menyetujui jadwal
  - Method `reject()` - Menolak jadwal

### Routes
```php
Route::post('application-schedules/{id}/approve', 'ApplicationScheduleController@approve')
    ->name('application-schedules.approve');
    
Route::post('application-schedules/{id}/reject', 'ApplicationScheduleController@reject')
    ->name('application-schedules.reject');
```

### Models
- `Application` - Status field untuk tracking
- `ApplicationSchedule` - Data jadwal
- `ApplicationAction` - Log semua aksi admin

## Database Schema

### application_schedules
```
- id
- application_id (FK)
- schedule_type (seminar/defense)
- waktu (datetime)
- ruang_id (FK)
- custom_place
- online_meeting
- note
- created_at
- updated_at
```

### applications
```
- id
- status (submitted/approved/scheduled/rejected/result/done)
- notes (untuk alasan penolakan)
- stage
- created_at
- updated_at
```

### application_actions
```
- id
- application_id (FK)
- action_type (schedule_approved/schedule_rejected)
- action_by (FK ke users)
- notes
- metadata (JSON)
- created_at
```

## Status Application

| Status | Keterangan |
|--------|-----------|
| `submitted` | Baru diajukan, menunggu review admin |
| `approved` | Disetujui dokumen, menunggu penjadwalan |
| `scheduled` | Jadwal sudah disetujui dan ditetapkan |
| `rejected` | Ditolak dengan alasan |
| `result` | Sudah selesai, menunggu nilai |
| `done` | Selesai semua proses |

## API Endpoints

### GET /admin/application-schedules (Ajax)
**Query Parameters:**
- `status_filter` (optional): 'pending' | 'approved' | 'rejected'

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "mahasiswa_name": "John Doe",
      "mahasiswa_nim": "123456",
      "schedule_type": "Seminar Proposal",
      "waktu": "20 Jan 2024 10:00",
      "ruang_name": "Ruang 101",
      "status": "submitted",
      "created_at": "19 Jan 2024 08:00"
    }
  ]
}
```

### POST /admin/application-schedules/{id}/approve
**Request:**
```json
{
  "notes": "Jadwal sudah sesuai, silakan lanjutkan persiapan"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Jadwal seminar berhasil disetujui"
}
```

### POST /admin/application-schedules/{id}/reject
**Request:**
```json
{
  "reason": "Waktu bentrok dengan jadwal lain, mohon ajukan ulang"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Jadwal seminar ditolak"
}
```

## Keamanan & Validasi

### Frontend Validation
- Alasan penolakan minimal 10 karakter
- Form tidak bisa disubmit tanpa isi reason (untuk reject)
- Tombol disabled saat processing untuk prevent double submit

### Backend Validation
- CSRF token validation
- Gate authorization check (`application_schedule_access`)
- Validate schedule exists (findOrFail)
- Validate notes/reason string format

### Database Transaction
Semua operasi approve/reject menggunakan DB transaction untuk:
- Update application status
- Create application action log
- Memastikan atomicity (all or nothing)

## UI/UX Features

### Visual Indicators
- 🟡 **Badge Warning** - Menunggu Persetujuan
- 🟢 **Badge Success** - Disetujui
- 🔴 **Badge Danger** - Ditolak
- 🔵 **Badge Info** - Submitted

### Auto-Reload
- Setelah approve/reject, semua DataTable otomatis reload
- Tidak perlu refresh halaman
- Data selalu up-to-date

### Responsive Design
- Mobile-friendly tabs
- Responsive table
- Modal works on all screen sizes

### Loading States
- Button shows spinner saat processing
- Button text berubah: "Memproses..."
- Button disabled untuk prevent double click

## Troubleshooting

### Jadwal Tidak Muncul di Tab Pending
**Kemungkinan:**
- Status application bukan 'submitted' atau 'approved'
- Data sudah masuk tab lain (approved/rejected)

**Solusi:**
- Cek tab "Semua Jadwal" untuk melihat semua data
- Cek status di database: `applications.status`

### Error Saat Approve/Reject
**Kemungkinan:**
- CSRF token expired
- Session timeout
- Database connection error

**Solusi:**
- Refresh halaman untuk regenerate CSRF token
- Login ulang jika session timeout
- Cek error log di `storage/logs/laravel.log`

### DataTable Tidak Load
**Kemungkinan:**
- JavaScript error
- Ajax request gagal
- Permission tidak sesuai

**Solusi:**
- Buka browser console untuk cek error
- Cek network tab untuk failed requests
- Verify user punya permission `application_schedule_access`

## Best Practices

### Untuk Admin
1. Selalu baca detail jadwal sebelum approve/reject
2. Berikan alasan jelas saat menolak
3. Gunakan catatan untuk komunikasi dengan mahasiswa
4. Periksa tab "Menunggu Persetujuan" secara berkala

### Untuk Developer
1. Selalu gunakan DB transaction untuk operasi critical
2. Log semua aksi admin ke `application_actions`
3. Validasi di frontend dan backend
4. Handle error dengan user-friendly message
5. Test dengan berbagai status application

## Future Enhancements

### Planned Features
- [ ] Bulk approve multiple schedules
- [ ] Email notification ke mahasiswa
- [ ] Filter by date range
- [ ] Export to PDF/Excel
- [ ] Schedule conflict detection
- [ ] Calendar view integration
- [ ] Push notification (real-time)
- [ ] Statistics dashboard

### Performance Optimization
- [ ] Cache frequent queries
- [ ] Lazy load modal content
- [ ] Optimize DataTable queries
- [ ] Add database indexes

## Changelog

### Version 1.0 (Current)
- ✅ Tab-based filtering system
- ✅ Quick approve/reject actions
- ✅ Modal forms with validation
- ✅ Auto-reload DataTables
- ✅ Action logging
- ✅ Status badges
- ✅ Responsive design
- ✅ AJAX error handling

---

**Dibuat:** Oktober 2025  
**Last Updated:** Oktober 2025  
**Maintainer:** Development Team

