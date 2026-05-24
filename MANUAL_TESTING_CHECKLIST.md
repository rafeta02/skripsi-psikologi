# ✅ Manual Testing Checklist

**Test Date:** _______________
**Tester Name:** _______________
**Environment:** □ Development □ Staging □ Production

---

## 🎯 PRE-TESTING SETUP

### Database Preparation
- [ ] Run: `php artisan migrate:fresh`
- [ ] Run: `php artisan db:seed --class=TestDataSeeder`
- [ ] Verify test accounts created

### File Preparation
Download/create these test files in a folder named `test-files`:
- [ ] `test_khs_1mb.pdf` - 1MB PDF file
- [ ] `test_krs_2mb.pdf` - 2MB PDF file
- [ ] `test_proposal_10mb.pdf` - 10MB PDF file
- [ ] `test_approval_2mb.pdf` - 2MB PDF file
- [ ] `test_plagiarism_8mb.pdf` - 8MB PDF file
- [ ] `test_thesis_15mb.pdf` - 15MB PDF file
- [ ] `test_oversized_25mb.pdf` - 25MB PDF (for negative testing)
- [ ] `test_wrong_format.docx` - DOCX file (for negative testing)

### Server Setup
- [ ] Run: `php artisan serve`
- [ ] Open browser: `http://localhost:8000`
- [ ] Clear browser cache

---

## 📝 TEST ACCOUNTS

### Admin
- **Email:** admin@test.com
- **Password:** password

### Dosen (3 accounts)
- **Dosen 1:** dosen1@test.com / password (Psikologi Klinis)
- **Dosen 2:** dosen2@test.com / password (Psikologi Pendidikan)
- **Dosen 3:** dosen3@test.com / password (Psikologi Sosial)

### Mahasiswa (3 accounts)
- **Mahasiswa 1:** mahasiswa1@test.com / password (Andi Pratama)
- **Mahasiswa 2:** mahasiswa2@test.com / password (Dewi Lestari)
- **Mahasiswa 3:** mahasiswa3@test.com / password (Candra Kusuma)

---

## 🔥 CRITICAL PATH TESTING

### TEST 1: SKRIPSI REGULER - FULL WORKFLOW

#### 1.1 Registration (Mahasiswa 1)
Login as: `mahasiswa1@test.com`

- [ ] Navigate to Dashboard → Skripsi
- [ ] Click "Skripsi Reguler"
- [ ] URL is `/frontend/choose-path`
- [ ] Page loads without errors
- [ ] **Step 1: Student Info**
  - [ ] Student name auto-filled
  - [ ] NIM auto-filled
- [ ] Click "Selanjutnya" (Next)
- [ ] **Step 2: Proposal 1**
  - [ ] Enter title: "Analisis Kecemasan Remaja di Era Digital"
  - [ ] Enter description (100+ words)
  - [ ] Select theme from dropdown (Select2 works, no clipping)
- [ ] Click "Selanjutnya"
- [ ] **Step 7: Upload Documents**
  - [ ] Upload KHS: `test_khs_1mb.pdf`
  - [ ] Upload KRS: `test_krs_2mb.pdf`
  - [ ] File inputs accept PDF
  - [ ] Preview shows file name
- [ ] Click "Kirim Pendaftaran"
- [ ] Success message appears
- [ ] Redirected to `/mahasiswa/aplikasi`
- [ ] Application card shows "Status: Submitted"
- [ ] Take screenshot: `01-skripsi-registration-success.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.2 Admin Verification
Login as: `admin@test.com`

- [ ] Navigate to Skripsi Registrations
- [ ] URL is `/admin/skripsi-registrations`
- [ ] Table shows Andi Pratama's application
- [ ] Click "Show" on Andi's application
- [ ] Student details visible
- [ ] Download KHS document (opens in browser)
- [ ] Download KRS document (opens in browser)
- [ ] Files are correctly named (e.g., `SKRIPSI_KHS_2019010001_...`)
- [ ] Click "Edit"
- [ ] Change status to "Approved"
- [ ] Click "Update"
- [ ] Success message appears
- [ ] Status badge shows "Approved"
- [ ] Take screenshot: `02-admin-approve-registration.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.3 Assign Pembimbing (Admin)
Still logged in as: `admin@test.com`

- [ ] Navigate to Application Assignments
- [ ] Click "Create Assignment"
- [ ] URL is `/admin/application-assignments/create`
- [ ] Select Application: "Andi Pratama - Analisis Kecemasan..."
- [ ] Select Dosen: "Dr. Ahmad Wijaya, M.Psi"
- [ ] Select Role: "Pembimbing"
- [ ] Set assigned date: (today's date)
- [ ] Click "Save"
- [ ] Success message appears
- [ ] Assignment appears in list
- [ ] Take screenshot: `03-admin-assign-pembimbing.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.4 Dosen Reviews Proposal
Login as: `dosen1@test.com`

- [ ] Navigate to Dashboard (auto-redirected)
- [ ] Statistics cards visible
- [ ] "Mahasiswa Bimbingan" shows 1
- [ ] Navigate to "Penugasan Pembimbingan"
- [ ] URL is `/dosen/task-assignments`
- [ ] Table shows Andi Pratama's assignment
- [ ] Click "Review" button
- [ ] Redirected to `/dosen/review-proposal/{id}`
- [ ] Mahasiswa info visible
- [ ] Documents downloadable
- [ ] **Fill review form:**
  - [ ] Decision: "Approved"
  - [ ] Score: 85
  - [ ] Feedback: "Proposal sangat baik. Metodologi jelas dan relevan."
- [ ] Click "Kirim Review"
- [ ] Success message appears
- [ ] Redirected back to task assignments
- [ ] Status changed to "Accepted"
- [ ] Take screenshot: `04-dosen-review-proposal.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.5 Skripsi Seminar Registration (Mahasiswa 1)
Login as: `mahasiswa1@test.com`

- [ ] Navigate to "Skripsi Seminar"
- [ ] Click "Daftar Seminar"
- [ ] URL is `/frontend/skripsi-seminars/create`
- [ ] **Fill form:**
  - [ ] Title: "Analisis Kecemasan Remaja di Era Digital"
  - [ ] Description: (detailed abstract, 200+ words)
  - [ ] Upload proposal: `test_proposal_10mb.pdf` (required)
  - [ ] Upload approval: `test_approval_2mb.pdf` (required)
  - [ ] Upload plagiarism: `test_plagiarism_8mb.pdf` (required)
  - [ ] Notes: "Siap untuk seminar proposal"
- [ ] All required fields marked with asterisk
- [ ] Click "Kirim Pendaftaran"
- [ ] Success message appears
- [ ] Redirected to seminar list
- [ ] New seminar visible with status "Submitted"
- [ ] Take screenshot: `05-seminar-registration-success.png`

**Negative Test:**
- [ ] Try submitting without uploading files → Error appears
- [ ] Try uploading `test_wrong_format.docx` → Error "must be PDF"
- [ ] Try uploading `test_oversized_25mb.pdf` → Error "max size 10MB"

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.6 Admin Verifies Seminar
Login as: `admin@test.com`

- [ ] Navigate to Skripsi Seminars
- [ ] Find Andi Pratama's seminar
- [ ] Click "Show"
- [ ] All documents downloadable
- [ ] Click "Edit"
- [ ] Change status to "Approved"
- [ ] Click "Update"
- [ ] Success message
- [ ] Take screenshot: `06-admin-approve-seminar.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.7 Create Schedule (Mahasiswa 1)
Login as: `mahasiswa1@test.com`

- [ ] Navigate to "Jadwal"
- [ ] Click "Buat Jadwal"
- [ ] **Fill form:**
  - [ ] Schedule type: "Skripsi Seminar" (auto-selected)
  - [ ] Date: (tomorrow's date)
  - [ ] Time: 14:00
  - [ ] Location: "Offline"
  - [ ] Room: "Ruang Seminar 1"
  - [ ] Notes: "Mohon konfirmasi kehadiran"
- [ ] Click "Buat Jadwal"
- [ ] Success message
- [ ] Schedule appears in list with status "Submitted"

**Test Online Option:**
- [ ] Create another schedule
- [ ] Select "Online"
- [ ] Room field hidden
- [ ] Meeting link field appears
- [ ] Enter Zoom link: "https://zoom.us/j/123456789"
- [ ] Submit successfully
- [ ] Take screenshot: `07-schedule-creation.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.8 Admin Approves Schedule
Login as: `admin@test.com`

- [ ] Navigate to Application Schedules
- [ ] Find Andi's schedule
- [ ] Click "Edit"
- [ ] Change status to "Approved"
- [ ] Update
- [ ] Take screenshot: `08-admin-approve-schedule.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.9 Report Seminar Result (Mahasiswa 1)
Login as: `mahasiswa1@test.com`

- [ ] Navigate to "Hasil Seminar"
- [ ] Click "Laporkan Hasil"
- [ ] **Fill form:**
  - [ ] Result: "Passed"
  - [ ] Notes: "Seminar berjalan lancar, reviewer memberikan feedback positif"
  - [ ] Upload BA documents (multiple): 2 PDF files
  - [ ] Upload attendance: 1 PDF
  - [ ] Upload forms (multiple): 2 PDF files
- [ ] Multiple file upload works (drag & drop or select multiple)
- [ ] Submit
- [ ] Success message
- [ ] Take screenshot: `09-report-seminar-result.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.10 Defense Registration (Mahasiswa 1)
Login as: `mahasiswa1@test.com`

- [ ] Navigate to "Sidang Skripsi"
- [ ] Click "Daftar Sidang"
- [ ] **Fill form:**
  - [ ] Title: "Analisis Kecemasan Remaja di Era Digital (Final)"
  - [ ] Abstract: (final abstract, 300+ words)
  - [ ] Upload thesis: `test_thesis_15mb.pdf` (max 20MB)
  - [ ] Upload approval: `test_approval_2mb.pdf`
  - [ ] Upload plagiarism: `test_plagiarism_8mb.pdf`
  - [ ] Upload revision proof: (optional, can skip)
- [ ] Submit
- [ ] Success message
- [ ] Status "Submitted"
- [ ] Take screenshot: `10-defense-registration.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.11 Admin Verifies Defense & Assigns Examiners
Login as: `admin@test.com`

- [ ] Navigate to Skripsi Defenses
- [ ] Find Andi's defense
- [ ] Approve defense
- [ ] Navigate to Application Assignments
- [ ] Create assignment: Dosen 2, role "Penguji"
- [ ] Create assignment: Dosen 3, role "Penguji"
- [ ] Take screenshot: `11-assign-examiners.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 1.12 Dosen Scores Defense
Login as: `dosen2@test.com`

- [ ] Navigate to "Nilai"
- [ ] Find Andi's application
- [ ] Click "Beri Nilai"
- [ ] URL is `/dosen/scoring/{id}`
- [ ] **Fill scoring form:**
  - [ ] Overall score: 88
  - [ ] Presentation score: 85 (optional)
  - [ ] Content score: 90 (optional)
  - [ ] Methodology score: 87 (optional)
  - [ ] Q&A score: 86 (optional)
  - [ ] Grade letter: A
  - [ ] Comments: "Presentasi sangat baik, penelitian solid, rekomendasi implementatif"
  - [ ] Recommendation: "Passed"
- [ ] Submit
- [ ] Success message
- [ ] Take screenshot: `12-dosen-scoring.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

### TEST 2: MBKM WORKFLOW (Mahasiswa 2)

#### 2.1 MBKM Registration
Login as: `mahasiswa2@test.com`

- [ ] Navigate to Dashboard → Skripsi
- [ ] Click "Skripsi MBKM"
- [ ] **Fill wizard:**
  - [ ] Step 1: Student info (auto-filled)
  - [ ] Step 2: MBKM program: "Program Pertukaran Mahasiswa"
  - [ ] Step 3: Proposal title: "Implementasi MBKM dalam Pembelajaran"
  - [ ] Step 4: Upload MBKM certificate
  - [ ] Step 5: Upload KHS & KRS
  - [ ] Step 6: Select research group
- [ ] Submit
- [ ] Success message
- [ ] Application type = "MBKM"
- [ ] Take screenshot: `13-mbkm-registration.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 2.2 MBKM Seminar Registration
Login as: `mahasiswa2@test.com` (after admin approval & pembimbing approval)

- [ ] Navigate to "Seminar MBKM"
- [ ] Click "Daftar Seminar"
- [ ] Fill MBKM seminar form
- [ ] All required documents uploaded
- [ ] Submit successfully
- [ ] Take screenshot: `14-mbkm-seminar-registration.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

### TEST 3: TIMELINE COMPONENT

#### 3.1 Timeline in Mahasiswa Detail Page
Login as: `mahasiswa1@test.com`

- [ ] Navigate to application detail
- [ ] Timeline component visible
- [ ] Shows 10 stages for Skripsi Reguler
- [ ] Current stage highlighted with primary color
- [ ] Completed stages show green checkmark
- [ ] Pending stages grayed out
- [ ] Status badge displays correctly
- [ ] Progress bar shows correct percentage
- [ ] Take screenshot: `15-timeline-mahasiswa.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 3.2 Timeline in Dosen Modal
Login as: `dosen1@test.com`

- [ ] Navigate to "Mahasiswa Bimbingan"
- [ ] Find Andi Pratama
- [ ] Click "Lihat Timeline" button
- [ ] Modal opens smoothly (no glitch)
- [ ] Timeline in compact horizontal mode
- [ ] All stages visible
- [ ] Modal closeable
- [ ] Take screenshot: `16-timeline-dosen-modal.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

#### 3.3 Timeline in Admin Monitoring
Login as: `admin@test.com`

- [ ] Navigate to "Monitoring"
- [ ] URL is `/admin/monitoring`
- [ ] All applications listed
- [ ] Timeline visible for each application
- [ ] Shows current progress
- [ ] Pembimbing info displayed
- [ ] "Lihat Detail" link works
- [ ] Pagination works (if >20 applications)
- [ ] Take screenshot: `17-monitoring-dashboard.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

### TEST 4: APPLICATION REPORTS

#### 4.1 Create Issue Report
Login as: `mahasiswa1@test.com`

- [ ] Navigate to "Laporan Kendala"
- [ ] Click "Buat Laporan"
- [ ] **Fill form:**
  - [ ] Title: "Kesulitan Akses Jurnal"
  - [ ] Priority: "High"
  - [ ] Description: (detailed issue description)
  - [ ] Upload evidence: 2 PDF files
- [ ] Submit
- [ ] Success message
- [ ] Report appears in list
- [ ] Take screenshot: `18-application-report.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

### TEST 5: ADMIN DASHBOARD

#### 5.1 Dashboard Statistics
Login as: `admin@test.com`

- [ ] Navigate to Dashboard
- [ ] URL is `/admin`
- [ ] **Statistics cards display:**
  - [ ] Total Aplikasi (count correct)
  - [ ] Pending Verifications (count correct)
  - [ ] Approved (count correct)
  - [ ] Ongoing Defenses
  - [ ] Total Mahasiswa
  - [ ] Total Dosen
  - [ ] Pending Assignments
- [ ] **Pending Verifications section:**
  - [ ] Registrations count
  - [ ] Seminars count
  - [ ] Defenses count
- [ ] **Recent Applications table:**
  - [ ] Shows last 10 applications
  - [ ] Student name visible
  - [ ] Type (Skripsi/MBKM) visible
  - [ ] Status badge correct
  - [ ] Action links work
- [ ] Take screenshot: `19-admin-dashboard.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

### TEST 6: DOSEN DASHBOARD

#### 6.1 Dashboard Features
Login as: `dosen1@test.com`

- [ ] Navigate to Dashboard
- [ ] URL is `/dosen`
- [ ] **Statistics cards:**
  - [ ] Mahasiswa Bimbingan count correct
  - [ ] Total Penugasan count correct
  - [ ] Pending Reviews count correct
  - [ ] Total Scores count correct
- [ ] **Recent Assignments:**
  - [ ] Shows last 5 assignments
  - [ ] Student info visible
  - [ ] Application type visible
  - [ ] Status badge correct
- [ ] Take screenshot: `20-dosen-dashboard.png`

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

## 🔒 SECURITY TESTS

### S1: Permission Tests

- [ ] **Test SEC-001:** Login as Mahasiswa, try to access `/admin` → 403 Forbidden
- [ ] **Test SEC-002:** Login as Mahasiswa, try to access `/dosen` → 403 Forbidden
- [ ] **Test SEC-003:** Login as Dosen, try to access `/admin` → 403 Forbidden
- [ ] **Test SEC-004:** Dosen 1 cannot see Dosen 2's assignments
- [ ] **Test SEC-005:** Mahasiswa 1 cannot see Mahasiswa 2's applications

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

## 🐛 REGRESSION TESTS (Previous Bugs)

- [ ] **BUG-001:** Modal on `/dosen/task-assignments` → No glitch, opens smoothly
- [ ] **BUG-002:** Select2 dropdown → No clipping, fully visible
- [ ] **BUG-003:** Wizard navigation → Next/Prev buttons work
- [ ] **BUG-004:** Date fields → No Carbon parsing errors
- [ ] **BUG-005:** File uploads → Accept file objects, no "must be string" error

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

## 📱 RESPONSIVE TESTS

### Device Testing
Test key pages on different viewports:

**Desktop (1920x1080):**
- [ ] Registration forms display correctly
- [ ] Tables readable
- [ ] Modals centered

**Laptop (1366x768):**
- [ ] All content visible
- [ ] No horizontal scroll

**Tablet (768x1024):**
- [ ] Mobile-friendly layout
- [ ] Touch-friendly buttons

**Mobile (375x667):**
- [ ] Stacked layout
- [ ] Forms usable
- [ ] Navigation accessible

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

## 🌐 BROWSER COMPATIBILITY

Test on:
- [ ] Chrome (latest) → All features work
- [ ] Firefox (latest) → All features work
- [ ] Edge (latest) → All features work
- [ ] Safari (if available) → All features work

**Result:** □ PASS □ FAIL  
**Notes:** _______________________________________________

---

## 📊 FINAL TEST SUMMARY

### Overall Results
- **Total Test Cases:** 100+
- **Passed:** _____
- **Failed:** _____
- **Pass Rate:** _____%

### Critical Issues Found
1. _____________________________________
2. _____________________________________
3. _____________________________________

### Recommendations
- [ ] All critical issues resolved
- [ ] Application ready for UAT
- [ ] Application ready for production

### Sign-off
**Tester:** _______________  
**Date:** _______________  
**Signature:** _______________

---

**End of Manual Testing Checklist**
