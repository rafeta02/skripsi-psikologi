# 🧪 Test Execution Guide - Quick Start

## 📋 Pre-Testing Checklist

### 1. Setup Test Environment
```bash
# Ensure database is seeded
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink
php artisan storage:link

# Start server
php artisan serve
```

### 2. Create Test Accounts

**Admin Account:**
- Email: `admin@test.com`
- Password: `password`
- Role: Administrator

**Dosen Accounts:**
- Email: `dosen1@test.com`, `dosen2@test.com`, `dosen3@test.com`
- Password: `password`
- Role: Dosen

**Mahasiswa Accounts:**
- Email: `mahasiswa1@test.com`, `mahasiswa2@test.com`, `mahasiswa3@test.com`
- Password: `password`
- Role: Mahasiswa

### 3. Prepare Test Files

Create sample PDF files:
- `test_khs_1mb.pdf` (1MB)
- `test_krs_5mb.pdf` (5MB)
- `test_proposal_10mb.pdf` (10MB)
- `test_plagiarism_8mb.pdf` (8MB)
- `test_approval_2mb.pdf` (2MB)
- `test_oversized_15mb.pdf` (15MB - for negative testing)

---

## 🎯 Test Execution Order

### Phase 1: SKRIPSI REGULER (Full Flow)

#### Test 1.1: Registration (Mahasiswa)
**URL:** `http://localhost:8000/frontend/choose-path`

**Steps:**
1. Login as `mahasiswa1@test.com`
2. Navigate to Dashboard → Menu Skripsi → Pilih "Skripsi Reguler"
3. Fill multi-step form:
   - **Step 1:** Student info (auto-filled)
   - **Step 2:** Proposal 1 (title, description, theme)
   - **Step 3:** Proposal 2 (optional)
   - **Step 4:** Proposal 3 (optional)
   - **Step 5:** Research group selection
   - **Step 6:** Upload KHS (use `test_khs_1mb.pdf`)
   - **Step 7:** Upload KRS (use `test_krs_5mb.pdf`)
4. Click "Kirim Pendaftaran"

**Expected:**
✅ Success message displayed
✅ Redirected to `/mahasiswa/aplikasi`
✅ Application card shows status "Submitted"
✅ Files uploaded with correct naming

**Screenshot Locations:**
- [ ] Registration form page 1
- [ ] Registration form page 7 (final)
- [ ] Success confirmation
- [ ] Application list with new application

---

#### Test 1.2: Admin Verification
**URL:** `http://localhost:8000/admin/skripsi-registrations`

**Steps:**
1. Logout, login as `admin@test.com`
2. Navigate to Skripsi Registrations
3. Find the newly created registration
4. Click "Show" to view details
5. Download and verify KHS & KRS files
6. Click "Approve" button

**Expected:**
✅ Registration details displayed correctly
✅ Documents downloadable
✅ Status changes to "Approved"
✅ Mahasiswa sees updated status

**Test Variations:**
- [ ] Test approve flow
- [ ] Test reject flow (with rejection note)
- [ ] Verify rejection note visible to mahasiswa

---

#### Test 1.3: Assign Pembimbing (Admin)
**URL:** `http://localhost:8000/admin/application-assignments/create`

**Steps:**
1. As admin, navigate to Application Assignments
2. Click "Create Assignment"
3. Select the application (mahasiswa1's application)
4. Select Dosen: `dosen1@test.com`
5. Select Role: "Pembimbing"
6. Set assigned date: today
7. Click "Save"

**Expected:**
✅ Assignment created successfully
✅ Assignment visible in list
✅ Dosen sees assignment in their dashboard

---

#### Test 1.4: Dosen Review (Dosen)
**URL:** `http://localhost:8000/dosen/task-assignments`

**Steps:**
1. Logout, login as `dosen1@test.com`
2. Navigate to Task Assignments
3. Find mahasiswa1's assignment
4. Click "Review" button
5. On review page, fill form:
   - Decision: "Approved"
   - Score: 85
   - Feedback: "Proposal bagus, lanjutkan penelitian"
6. Click "Kirim Review"

**Expected:**
✅ Review form displays mahasiswa info correctly
✅ Documents accessible
✅ Review submitted successfully
✅ Application status changes to "Approved"
✅ Mahasiswa can see feedback

**Test Variations:**
- [ ] Test "Revision" decision (should show revision notes field)
- [ ] Test "Rejected" decision
- [ ] Test required field validation

---

#### Test 1.5: Skripsi Seminar Registration (Mahasiswa)
**URL:** `http://localhost:8000/frontend/skripsi-seminars/create`

**Steps:**
1. Login as `mahasiswa1@test.com`
2. Navigate to Skripsi Seminars
3. Click "Daftar Seminar"
4. Fill form:
   - Title: "Analisis Psikologi Remaja"
   - Description: "Penelitian tentang perilaku remaja..."
   - Upload proposal: `test_proposal_10mb.pdf`
   - Upload approval: `test_approval_2mb.pdf`
   - Upload plagiarism: `test_plagiarism_8mb.pdf`
   - Notes: "Siap untuk seminar"
5. Click "Kirim Pendaftaran"

**Expected:**
✅ All file fields marked as required
✅ Files upload successfully
✅ Seminar registered
✅ Status "Submitted"
✅ Visible in seminar list

**Test Variations:**
- [ ] Test without uploading required files (should show error)
- [ ] Test with oversized file (should show error)
- [ ] Test with non-PDF file (should show error)

---

#### Test 1.6: Admin Verify Seminar
**URL:** `http://localhost:8000/admin/skripsi-seminars`

**Steps:**
1. Login as `admin@test.com`
2. Navigate to Skripsi Seminars
3. Find mahasiswa1's seminar
4. Click "Show" to view
5. Verify all documents
6. Click "Approve"

**Expected:**
✅ Seminar details displayed
✅ All documents downloadable
✅ Approval successful
✅ Status changes to "Approved"

---

#### Test 1.7: Assign Reviewer (Admin)
**URL:** `http://localhost:8000/admin/application-assignments/create`

**Steps:**
1. As admin, create new assignment
2. Select mahasiswa1's application
3. Select Dosen: `dosen2@test.com`
4. Role: "Reviewer"
5. Save

**Repeat for second reviewer:**
6. Create another assignment
7. Select same application
8. Select Dosen: `dosen3@test.com`
9. Role: "Reviewer"
10. Save

**Expected:**
✅ Both reviewers assigned
✅ Visible in assignments list
✅ Both dosen see tasks

---

#### Test 1.8: Schedule Creation (Mahasiswa/Admin)
**URL:** `http://localhost:8000/frontend/application-schedules/create`

**Steps:**
1. Login as `mahasiswa1@test.com`
2. Navigate to Jadwal
3. Click "Buat Jadwal"
4. Fill form:
   - Schedule type: "Skripsi Seminar" (auto-selected)
   - Date: tomorrow
   - Time: 14:00
   - Location: Offline
   - Room: Select from dropdown
   - Notes: "Mohon konfirmasi"
5. Click "Buat Jadwal"

**Expected:**
✅ Schedule created
✅ Status "Submitted"
✅ Admin can verify

**Test Online Meeting:**
- [ ] Select "Online" location
- [ ] Enter Zoom/Meet link
- [ ] Verify link validation

---

#### Test 1.9: Admin Approve Schedule
**URL:** `http://localhost:8000/admin/application-schedules`

**Steps:**
1. Login as admin
2. Find schedule
3. Click approve
4. Verify status changes to "Scheduled"

**Expected:**
✅ Schedule approved
✅ Application status "Scheduled"
✅ Mahasiswa sees updated status

---

#### Test 1.10: Report Seminar Result (Mahasiswa)
**URL:** `http://localhost:8000/frontend/application-result-seminars/create`

**Steps:**
1. Login as `mahasiswa1@test.com`
2. Navigate to result seminars
3. Click "Buat Laporan"
4. Fill form:
   - Result: "Passed"
   - Notes: "Seminar berjalan lancar"
   - Upload BA documents (multiple)
   - Upload attendance
   - Upload assessment forms
5. Submit

**Expected:**
✅ Result recorded
✅ Multiple file upload works
✅ Status updated
✅ Documents accessible

**Test Revision Flow:**
- [ ] Select "Revision" result
- [ ] Set revision deadline
- [ ] Verify deadline field appears

---

#### Test 1.11: Defense Registration (Mahasiswa)
**URL:** `http://localhost:8000/frontend/skripsi-defenses/create`

**Steps:**
1. Login as `mahasiswa1@test.com`
2. Navigate to Sidang Skripsi
3. Click "Daftar Sidang"
4. Fill form:
   - Title: "Analisis Psikologi Remaja (Final)"
   - Abstract: "Penelitian ini menganalisis..."
   - Upload thesis document (max 20MB)
   - Upload approval
   - Upload plagiarism check
   - Upload revision proof (optional)
5. Submit

**Expected:**
✅ Defense registered
✅ Large file (20MB) upload works
✅ Optional field can be skipped
✅ Status "Submitted"

---

#### Test 1.12: Admin Verify Defense
**URL:** `http://localhost:8000/admin/skripsi-defenses`

**Steps:**
1. Login as admin
2. Find defense registration
3. Verify documents
4. Approve

**Expected:**
✅ Defense approved
✅ Ready for examiner assignment

---

#### Test 1.13: Assign Examiners (Admin)
**URL:** `http://localhost:8000/admin/application-assignments/create`

**Steps:**
1. Create assignment for examiner 1 (dosen2, role: Examiner)
2. Create assignment for examiner 2 (dosen3, role: Examiner)

**Expected:**
✅ Examiners assigned
✅ Visible in their dashboards

---

#### Test 1.14: Schedule Defense
**URL:** `http://localhost:8000/frontend/application-schedules/create`

**Steps:**
1. Login as mahasiswa1
2. Create schedule for defense
3. Similar to seminar scheduling
4. Submit and wait for admin approval

**Expected:**
✅ Defense scheduled
✅ Admin approves
✅ Status "Scheduled"

---

#### Test 1.15: Dosen Scoring (Dosen)
**URL:** `http://localhost:8000/dosen/scoring/{application_id}`

**Steps:**
1. Login as `dosen2@test.com` (examiner)
2. Navigate to Scores
3. Find mahasiswa1's defense
4. Click "Beri Nilai"
5. Fill scoring form:
   - Overall score: 88
   - Presentation score: 85
   - Content score: 90
   - Methodology score: 87
   - Q&A score: 86
   - Grade letter: A
   - Comments: "Presentasi sangat baik..."
   - Recommendation: "Passed"
6. Submit

**Expected:**
✅ Score recorded
✅ All fields optional except overall, comments, recommendation
✅ Success message

---

#### Test 1.16: Report Defense Result (Mahasiswa)
**URL:** `http://localhost:8000/frontend/application-result-defenses/create`

**Steps:**
1. Login as mahasiswa1
2. Report final defense result
3. Upload all final documents
4. Submit

**Expected:**
✅ Final result recorded
✅ Process complete
✅ Status "Done"

---

### Phase 2: SKRIPSI MBKM (Full Flow)

#### Test 2.1: MBKM Registration
**URL:** `http://localhost:8000/frontend/choose-path`

**Steps:**
1. Login as `mahasiswa2@test.com`
2. Choose "Skripsi MBKM"
3. Fill MBKM-specific fields:
   - MBKM program details
   - Upload MBKM certificate
   - Upload KHS, KRS
   - Select research group
4. Submit

**Expected:**
✅ MBKM registration created
✅ Type = "mbkm"
✅ MBKM certificate uploaded

---

#### Test 2.2-2.4: Admin & Dosen Flow (Similar to Skripsi)
- [ ] Admin verifies MBKM registration
- [ ] Admin assigns pembimbing
- [ ] Dosen reviews MBKM proposal

---

#### Test 2.5: MBKM Seminar Registration
**URL:** `http://localhost:8000/frontend/mbkm-seminars/create`

**Steps:**
1. Login as mahasiswa2
2. Register MBKM seminar
3. Upload required documents
4. Submit

**Expected:**
✅ MBKM seminar registered
✅ Different from Skripsi Seminar
✅ MBKM-specific fields present

---

#### Test 2.6-2.11: Remaining MBKM Flow
- [ ] Admin verifies MBKM seminar
- [ ] Assign reviewers
- [ ] Schedule MBKM seminar (formal scheduling)
- [ ] Register defense (uses SkripsiDefense)
- [ ] Dosen scoring
- [ ] Final result

**Note:** MBKM uses formal seminar scheduling (ApplicationSchedule), unlike Skripsi Reguler

---

### Phase 3: Cross-Cutting Features

#### Test 3.1: Timeline Component
**Locations to test:**
- Mahasiswa application detail page
- Dosen mahasiswa bimbingan modal
- Admin monitoring dashboard

**Test Cases:**
- [ ] Timeline shows 10 stages for Skripsi
- [ ] Timeline shows 11 stages for MBKM
- [ ] Current stage highlighted
- [ ] Completed stages show checkmark
- [ ] Status badges correct
- [ ] Compact mode in modal
- [ ] Vertical mode in detail pages

---

#### Test 3.2: Application Report
**URL:** `http://localhost:8000/frontend/application-reports/create`

**Steps:**
1. Login as any mahasiswa
2. Create issue report
3. Set priority (high/medium/low)
4. Enter description
5. Upload evidence files
6. Submit

**Admin Response:**
7. Login as admin
8. View reports
9. Respond to report
10. Update status

**Expected:**
✅ Report created
✅ Admin can respond
✅ Status tracking works
✅ Evidence files accessible

---

#### Test 3.3: Admin Dashboard
**URL:** `http://localhost:8000/admin`

**Test Statistics Cards:**
- [ ] Total applications count correct
- [ ] Pending verifications count correct
- [ ] Approved applications count correct
- [ ] Ongoing defenses count correct
- [ ] Total mahasiswa count
- [ ] Total dosen count
- [ ] Pending assignments count

**Test Pending Verifications:**
- [ ] Registrations count correct
- [ ] Seminars count correct
- [ ] Defenses count correct
- [ ] Links work

**Test Recent Applications:**
- [ ] Shows last 10 applications
- [ ] Correct data displayed
- [ ] Links work

---

#### Test 3.4: Monitoring Dashboard
**URL:** `http://localhost:8000/admin/monitoring`

**Steps:**
1. Login as admin
2. Navigate to Monitoring
3. View all applications with timeline
4. Check pagination
5. Click "Lihat Detail" for each

**Expected:**
✅ All applications listed
✅ Timeline visible for each
✅ Pembimbing info displayed
✅ Pagination works
✅ Detail links work

---

#### Test 3.5: Dosen Dashboard Features
**URL:** `http://localhost:8000/dosen`

**Test Dashboard:**
- [ ] Statistics correct
- [ ] Recent assignments shown
- [ ] Quick links work

**Test Mahasiswa Bimbingan:**
- [ ] All supervised students listed
- [ ] Timeline modal opens
- [ ] Timeline shows correct progress
- [ ] Detail link works

**Test Task Assignments:**
- [ ] All assignments listed
- [ ] Review button works
- [ ] Status badges correct

**Test Scores:**
- [ ] All scored applications shown
- [ ] Scores displayed correctly

---

## 🐛 Regression Testing (Previous Bugs)

### Bug Fix Verification:
- [ ] Modal glitch on dosen/task-assignments (should not glitch)
- [ ] Select2 dropdown clipping (should not clip)
- [ ] Wizard navigation (next/prev works)
- [ ] Carbon date parsing (no errors)
- [ ] File upload validation (correct messages)

---

## 🔒 Security Testing

### Permission Tests:
- [ ] Mahasiswa cannot access `/admin/*`
- [ ] Mahasiswa cannot access `/dosen/*`
- [ ] Dosen cannot access `/admin/*`
- [ ] Dosen can only see own assignments
- [ ] Mahasiswa can only see own applications

### SQL Injection Tests:
- [ ] Try `' OR '1'='1` in search fields
- [ ] Try SQL commands in text inputs
- [ ] All should be sanitized by Eloquent

### CSRF Tests:
- [ ] Forms without CSRF token rejected
- [ ] File uploads protected

---

## 📱 Responsive Testing

Test all key pages on:
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

**Key pages to test:**
- Registration forms
- Dashboard (all roles)
- Detail pages
- Modals

---

## 🌐 Browser Compatibility

Test on:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Edge (latest)
- [ ] Safari (if available)

**Focus on:**
- File uploads
- Select2 dropdowns
- Modals
- Responsive layouts

---

## 📊 Test Results Template

```markdown
## Test Execution Report

**Date:** YYYY-MM-DD
**Tester:** [Your Name]
**Environment:** Development

### Summary
- Total Test Cases: 150+
- Passed: XX
- Failed: XX
- Skipped: XX
- Pass Rate: XX%

### Critical Issues Found
1. [Issue ID] - [Description]
   - Severity: Critical/High/Medium/Low
   - Steps to Reproduce: ...
   - Expected: ...
   - Actual: ...
   - Screenshot: ...

### Recommendations
- [ ] Fix critical issues before deployment
- [ ] Additional testing needed for: ...
- [ ] Performance optimization required for: ...
```

---

## ✅ Test Completion Checklist

- [ ] All Skripsi Reguler flows tested
- [ ] All MBKM flows tested
- [ ] Timeline component verified
- [ ] Admin dashboard tested
- [ ] Monitoring dashboard tested
- [ ] Dosen features tested
- [ ] Security tests passed
- [ ] Responsive tests passed
- [ ] Browser compatibility verified
- [ ] All critical bugs fixed
- [ ] Test report documented

---

**Estimated Testing Time:** 4-6 hours for complete manual testing
**Recommended:** Test in pairs (one tester, one recorder)

