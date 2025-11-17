# 🚀 Quick Start Guide - Skripsi Admin System

## Langkah Cepat untuk Mulai Testing

### 1. ⚡ Run Migrasi (Jika Belum)

```bash
php artisan migrate
```

**Expected Output:**
```
Migrating: 2025_10_12_145442_create_application_actions_table
Migrated:  2025_10_12_145442_create_application_actions_table
Migrating: 2025_10_12_145527_add_admin_fields_to_skripsi_registrations_table
Migrated:  2025_10_12_145527_add_admin_fields_to_skripsi_registrations_table
```

### 2. 🔑 Verifikasi Routes

```bash
php artisan route:list | findstr skripsi
```

**Expected Routes:**
- `admin.skripsi.dashboard`
- `admin.skripsi.dashboard.data`
- `admin.skripsi.dashboard.chart-data`
- `admin.skripsi-registrations.approve`
- `admin.skripsi-registrations.reject`
- `admin.skripsi-registrations.request-revision`

### 3. 🧹 Clear Cache

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### 4. 🌐 Test di Browser

#### a) Login sebagai Admin
```
URL: http://localhost:8000/admin
```

#### b) Akses Dashboard Skripsi
```
URL: http://localhost:8000/admin/skripsi/dashboard
```

**Apa yang Harus Terlihat:**
- ✅ 4 Statistics cards (Total, Pending, Approved, Rejected)
- ✅ Pie chart untuk stage distribution
- ✅ Approval rate progress bar
- ✅ DataTable dengan list pendaftaran
- ✅ Action buttons (Approve/Reject) untuk status "submitted"

#### c) Test Show Page
```
URL: http://localhost:8000/admin/skripsi-registrations/{id}
```
Ganti `{id}` dengan ID pendaftaran yang ada.

**Apa yang Harus Terlihat:**
- ✅ Card "Informasi Mahasiswa" dengan data lengkap
- ✅ Card "Informasi Skripsi" dengan judul dan abstrak
- ✅ Card "Dokumen Persyaratan" dengan preview buttons
- ✅ Card "Status Pendaftaran" dengan badge
- ✅ Card "Aksi Admin" (jika status = submitted)

#### d) Test Action - Approve
1. Klik tombol **"Setujui Pendaftaran"** (hijau)
2. Pilih dosen pembimbing dari dropdown
3. (Optional) Tambahkan catatan
4. Klik **"Setujui"**

**Expected Result:**
- ✅ Modal tertutup
- ✅ Sweet Alert success notification
- ✅ Page reload dengan status "Approved"
- ✅ Dosen pembimbing ter-assign
- ✅ Action ter-log di timeline

#### e) Test Action - Reject
1. Klik tombol **"Tolak Pendaftaran"** (merah)
2. Isi alasan penolakan (minimal 10 karakter)
3. Klik **"Tolak"**

**Expected Result:**
- ✅ Modal tertutup
- ✅ Sweet Alert success notification
- ✅ Page reload dengan status "Rejected"
- ✅ Alasan penolakan tampil di card status
- ✅ Action ter-log di timeline

#### f) Test Action - Request Revision
1. Klik tombol **"Minta Revisi"** (kuning)
2. Isi catatan revisi (minimal 10 karakter)
3. Klik **"Kirim Permintaan Revisi"**

**Expected Result:**
- ✅ Modal tertutup
- ✅ Sweet Alert success notification
- ✅ Page reload dengan status "Revision Requested"
- ✅ Catatan revisi tampil di card status
- ✅ Action ter-log di timeline

#### g) Test Document Preview
1. Di show page, klik tombol **"Preview"** pada dokumen KHS/KRS
2. Modal akan muncul dengan PDF viewer
3. PDF dapat di-scroll
4. Klik **"Download"** untuk download file

### 5. ✅ Verifikasi Database

#### Check Application Actions
```sql
SELECT * FROM application_actions ORDER BY created_at DESC LIMIT 5;
```

**Expected Columns:**
- `id`, `application_id`, `action_type`, `action_by`, `notes`, `metadata`, `created_at`, `updated_at`

#### Check Skripsi Registration Updates
```sql
SELECT id, assigned_supervisor_id, approval_date, rejection_reason, revision_notes 
FROM skripsi_registrations 
WHERE assigned_supervisor_id IS NOT NULL OR rejection_reason IS NOT NULL;
```

### 6. 📱 Test Navigation Menu

Di sidebar, cari menu **"Skripsi Management"** dengan ikon 🎓

**Submenu yang Harus Ada:**
- 📊 Dashboard Skripsi
- 📝 Pendaftaran Skripsi
- 🎤 Seminar Skripsi
- ⚖️ Sidang Skripsi

Klik masing-masing untuk memastikan tidak ada error.

---

## 🐛 Troubleshooting

### Error: Route not found
**Solution:**
```bash
php artisan route:clear
php artisan route:cache
```

### Error: Class ApplicationAction not found
**Solution:**
```bash
composer dump-autoload
```

### Error: AJAX call failed (500)
**Possible Causes:**
1. Check `storage/logs/laravel.log` untuk error detail
2. Pastikan CSRF token ada di page (check `<meta name="csrf-token">`)
3. Pastikan user punya permission yang sesuai

### Error: DataTable not loading
**Solution:**
1. Open browser console (F12)
2. Check for JavaScript errors
3. Verify route `admin.skripsi.dashboard.data` exists:
   ```bash
   php artisan route:list | findstr dashboard.data
   ```

### Error: Modal not showing
**Solution:**
1. Clear browser cache
2. Check if jQuery and Bootstrap are loaded
3. Check browser console for errors

---

## 📊 Test Scenarios

### Scenario 1: Complete Approval Flow
1. Create new skripsi registration (as mahasiswa)
2. Login as admin
3. Go to dashboard, see new submission in table
4. Click "View" button
5. Click "Approve", select supervisor, submit
6. Verify status changed to "Approved"
7. Verify supervisor assigned
8. Check timeline shows approval action

### Scenario 2: Rejection Flow
1. Find submission with status "submitted"
2. Click "Reject", enter reason (at least 10 chars)
3. Submit rejection
4. Verify status changed to "Rejected"
5. Verify rejection reason displays
6. Check timeline shows rejection action

### Scenario 3: Revision Flow
1. Find submission with status "submitted"
2. Click "Request Revision", enter notes
3. Submit revision request
4. Verify status changed to "Revision Requested"
5. Verify revision notes display
6. Check timeline shows revision action

### Scenario 4: Multiple Actions
1. Start with "submitted" status
2. Request revision
3. Student resubmits (manually update status back to "submitted")
4. Approve the submission
5. Check timeline shows ALL actions in order

---

## 🎯 Success Criteria

✅ **Dashboard loads without errors**
✅ **Statistics show correct numbers**
✅ **Charts display data**
✅ **DataTable loads and is interactive**
✅ **Show page displays all information**
✅ **Document preview works**
✅ **Approve action works and logs**
✅ **Reject action works and logs**
✅ **Revision request works and logs**
✅ **Navigation menu works**
✅ **No console errors**
✅ **No server errors (500)**

---

## 💡 Tips

1. **Use Chrome DevTools**: Press F12 to monitor network requests and console
2. **Check Laravel Logs**: `storage/logs/laravel.log` for server errors
3. **Test with Different Users**: Test dengan role admin yang berbeda
4. **Test Edge Cases**: Empty data, invalid inputs, etc.
5. **Mobile Testing**: Test responsiveness di mobile view

---

## 📞 Need Help?

Jika ada error atau pertanyaan:
1. Check `SKRIPSI_ADMIN_IMPLEMENTATION_COMPLETE.md` untuk dokumentasi lengkap
2. Review code di controller dan views
3. Check database migration files
4. Review routes configuration

---

**Happy Testing! 🎉**

