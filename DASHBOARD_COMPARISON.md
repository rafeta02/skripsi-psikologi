# Perbandingan Dashboard Dosen vs Mahasiswa

## 📊 Overview

Sistem ini memiliki **2 dashboard terpisah** dengan struktur dan fungsi yang berbeda:

### 1. Dashboard Dosen (`/dosen`)
- **Layout:** AdminLTE dengan **Sidebar Navigation**
- **Target User:** Dosen/Lecturer
- **Focus:** Task management, bimbingan mahasiswa, scoring

### 2. Dashboard Mahasiswa (`/mahasiswa`)
- **Layout:** AdminLTE dengan **Top Navigation**
- **Target User:** Mahasiswa/Student
- **Focus:** Aplikasi skripsi, bimbingan, jadwal, dokumen

---

## 🎨 Desain & Layout

| Aspek | Dashboard Dosen | Dashboard Mahasiswa |
|-------|----------------|-------------------|
| **Navigation** | Sidebar (Vertical) | Top Navbar (Horizontal) |
| **Menu Position** | Kiri (Fixed Sidebar) | Atas (Top Bar) |
| **Layout Class** | `sidebar-mini layout-fixed` | `hold-transition layout-top-nav` |
| **Color Scheme** | AdminLTE default (Dark sidebar) | Purple theme (#22004C) |
| **Responsive** | Collapsible sidebar | Collapsible navbar |
| **Logo Position** | Sidebar top | Navbar left |

---

## 📋 Menu Structure

### Dashboard Dosen (5 Menu di Sidebar)
1. **Dashboard** - Overview & statistics
2. **Mahasiswa Bimbingan** - List mahasiswa yang dibimbing
3. **Task Assignments** - Assignment dari admin dengan respond action
4. **Application Scores** - Nilai yang telah diberikan
5. **Profile** - Profile dosen

### Dashboard Mahasiswa (6 Menu di Top Navbar)
1. **Dashboard** - Overview & statistics
2. **Aplikasi Saya** - List semua aplikasi
3. **Bimbingan** - Info dosen pembimbing
4. **Jadwal** - Jadwal seminar/sidang
5. **Dokumen** - Kelola dokumen
6. **Profile** - Profile mahasiswa

---

## 🔧 Technical Details

### Dashboard Dosen
```
Controller: app/Http/Controllers/Dosen/DashboardController.php
Routes: /dosen/* (prefix: dosen, namespace: Dosen)
Layout: resources/views/layouts/dosen.blade.php
Menu: resources/views/partials/menu-dosen.blade.php
Views: resources/views/dosen/*.blade.php
```

### Dashboard Mahasiswa
```
Controller: app/Http/Controllers/Mahasiswa/DashboardController.php
Routes: /mahasiswa/* (prefix: mahasiswa, namespace: Mahasiswa)
Layout: resources/views/layouts/mahasiswa.blade.php
Views: resources/views/mahasiswa/*.blade.php
```

---

## 📊 Fitur Utama

### Dashboard Dosen

#### Statistics Cards:
- Total Mahasiswa Bimbingan
- Task Pending
- Task Completed
- Scores Given

#### Key Features:
- ✅ Accept/Reject task assignments
- ✅ View mahasiswa bimbingan dengan filter supervisor
- ✅ View scores yang telah diberikan
- ✅ Profile dosen dengan bidang keilmuan
- ✅ Recent assignments table

### Dashboard Mahasiswa

#### Statistics Cards:
- Total Aplikasi
- Aplikasi Aktif
- Aplikasi Selesai

#### Key Features:
- ✅ View aplikasi aktif dengan progress
- ✅ List dosen pembimbing & status
- ✅ View jadwal dengan link meeting
- ✅ Quick links upload dokumen
- ✅ Profile mahasiswa lengkap
- ✅ Recent applications table

---

## 🗄️ Database Relations

### Dashboard Dosen menggunakan:
- `ApplicationAssignment` (filter by `lecturer_id`)
- `ApplicationScore` (filter by `examiner_id`)
- `Dosen` model untuk profile

### Dashboard Mahasiswa menggunakan:
- `Application` (filter by `mahasiswa_id`)
- `ApplicationAssignment` untuk dosen pembimbing
- `ApplicationSchedule` untuk jadwal
- `Mahasiswa` model untuk profile

---

## 🎯 User Flow

### Dosen:
1. Login → Dashboard Dosen (`/dosen`)
2. Lihat task assignments baru
3. Accept/Reject assignment
4. Lihat mahasiswa bimbingan
5. Input scores (via admin panel)
6. View profile

### Mahasiswa:
1. Login → Dashboard Mahasiswa (`/mahasiswa`)
2. Buat aplikasi baru (choose path)
3. Upload dokumen persyaratan
4. Tunggu assignment dosen
5. Lihat jadwal seminar/sidang
6. View profile & update data

---

## 🔐 Security & Access

### Dashboard Dosen:
- Middleware: `auth`
- Data scope: Hanya data assignment untuk dosen tersebut
- Identification: Via `nip` atau `nidn` match dengan user email

### Dashboard Mahasiswa:
- Middleware: `auth`
- Data scope: Hanya data aplikasi mahasiswa tersebut
- Identification: Via `user->mahasiswa_id`
- Auto-redirect jika profile belum dibuat

---

## 🌐 URL Structure

### Dosen Routes:
```
/dosen                          → Dashboard
/dosen/mahasiswa-bimbingan      → Mahasiswa Bimbingan
/dosen/task-assignments         → Task Assignments
/dosen/scores                   → Application Scores
/dosen/profile                  → Profile
/dosen/assignments/{id}/respond → Respond to Assignment
```

### Mahasiswa Routes:
```
/mahasiswa          → Dashboard
/mahasiswa/aplikasi → Aplikasi Saya
/mahasiswa/bimbingan → Bimbingan
/mahasiswa/jadwal   → Jadwal
/mahasiswa/dokumen  → Dokumen
/mahasiswa/profile  → Profile
```

---

## 📱 Responsive Design

### Dashboard Dosen:
- Sidebar collapse di mobile
- Toggle button untuk show/hide sidebar
- Table responsive dengan scroll horizontal
- Cards stack vertical di mobile

### Dashboard Mahasiswa:
- Navbar collapse dengan hamburger menu
- Horizontal menu → vertical dropdown di mobile
- Full width cards di mobile
- Table responsive dengan scroll

---

## 🎨 Visual Differences

### Dashboard Dosen:
- **Sidebar kiri** dengan menu vertical
- Background sidebar gelap
- Content area di kanan
- Fixed sidebar (tidak scroll dengan content)
- User dropdown di sidebar

### Dashboard Mahasiswa:
- **Top navbar** dengan menu horizontal
- Purple gradient navbar (#22004C)
- Full width content
- Navbar fixed di atas
- User dropdown di navbar kanan

---

## 📝 Best Practices Implemented

### Both Dashboards:
✅ Clean separation of concerns (Controller → View)
✅ Proper middleware authentication
✅ Data scoping for security
✅ Responsive design
✅ Empty state handling
✅ Error handling with alerts
✅ Consistent naming conventions
✅ Reusable layout components
✅ Badge system for status
✅ Icon usage for better UX

---

## 🚀 Quick Start

### Untuk Dosen:
```bash
# Akses dashboard
http://your-domain.com/dosen

# Atau dari login, pilih role dosen
```

### Untuk Mahasiswa:
```bash
# Akses dashboard
http://your-domain.com/mahasiswa

# Atau dari login, pilih role mahasiswa
```

---

## 📚 Documentation Files

1. **DOSEN_DASHBOARD_GUIDE.md** - Panduan lengkap dashboard dosen
2. **MAHASISWA_DASHBOARD_GUIDE.md** - Panduan lengkap dashboard mahasiswa
3. **DASHBOARD_COMPARISON.md** - File ini (perbandingan)

---

## 🔄 Migration Path

Jika ingin migrasi dari dashboard lama ke dashboard baru:

### Untuk Mahasiswa:
- Dashboard lama: `/home` (route: `frontend.home`)
- Dashboard baru: `/mahasiswa` (route: `mahasiswa.dashboard`)
- Bisa keep keduanya atau redirect dari `/home` ke `/mahasiswa`

### Untuk Dosen:
- Tidak ada dashboard lama
- Langsung gunakan `/dosen`

---

## ✨ Key Advantages

### Separation of Concerns:
- Dosen dan Mahasiswa punya dashboard terpisah
- Tidak saling interfere
- Mudah maintenance dan development

### User Experience:
- Interface disesuaikan dengan kebutuhan role
- Navigation yang intuitif
- Clear information hierarchy

### Scalability:
- Mudah tambah fitur baru per role
- Independent development
- Flexible untuk customization

---

## 🎯 Conclusion

Kedua dashboard sudah implement dengan baik sesuai kebutuhan masing-masing role:

- **Dashboard Dosen** cocok untuk task management dengan sidebar navigation
- **Dashboard Mahasiswa** cocok untuk tracking aplikasi dengan top navigation

Keduanya menggunakan AdminLTE framework tapi dengan layout berbeda untuk optimal user experience! 🎉
