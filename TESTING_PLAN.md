# 🧪 Comprehensive Testing Plan - Thesis Management System

**Date:** 2026-05-09
**Purpose:** Test all forms and workflows for Skripsi Reguler & MBKM
**Scope:** Complete end-to-end testing from registration to graduation

---

## 📋 Test Coverage Overview

### Actors:
1. **Mahasiswa** - Student workflows
2. **Dosen** - Supervisor/reviewer workflows
3. **Administrator** - Admin verification workflows

### Workflows:
1. **Skripsi Reguler** (10 stages)
2. **Skripsi MBKM** (11 stages)

---

## 🎯 Test Scenarios

### A. SKRIPSI REGULER WORKFLOW

#### Stage 1: Pendaftaran Skripsi
**Form:** `/frontend/skripsi/create`
**Actor:** Mahasiswa

**Test Cases:**
- [ ] TC-SR-001: Access form with valid mahasiswa account
- [ ] TC-SR-002: Fill all required fields (student info, proposals, themes)
- [ ] TC-SR-003: Upload KHS document (PDF, max 10MB)
- [ ] TC-SR-004: Upload KRS document (PDF, max 10MB)
- [ ] TC-SR-005: Submit form with valid data
- [ ] TC-SR-006: Verify file naming convention (SKRIPSI_KHS_{NIM}_{NAMA}...)
- [ ] TC-SR-007: Test Select2 dropdown functionality
- [ ] TC-SR-008: Test wizard navigation (next/prev buttons)
- [ ] TC-SR-009: Validation: missing required fields
- [ ] TC-SR-010: Validation: file size exceeds limit
- [ ] TC-SR-011: Validation: wrong file format (non-PDF)

**Expected Result:**
- Application created with status 'submitted'
- Files uploaded successfully
- Redirect to application list

---

#### Stage 2: Verifikasi Administrator
**Page:** `/admin/skripsi-registrations`
**Actor:** Administrator

**Test Cases:**
- [ ] TC-SR-012: View pending registrations list
- [ ] TC-SR-013: Open registration detail page
- [ ] TC-SR-014: View uploaded documents (KHS, KRS)
- [ ] TC-SR-015: Approve registration
- [ ] TC-SR-016: Reject registration with notes
- [ ] TC-SR-017: Verify application status changes to 'approved'
- [ ] TC-SR-018: Verify application status changes to 'rejected'

**Expected Result:**
- Admin can approve/reject
- Status updates correctly
- Mahasiswa sees updated status

---

#### Stage 3: Penugasan Pembimbing
**Page:** `/admin/application-assignments/create`
**Actor:** Administrator

**Test Cases:**
- [ ] TC-SR-019: Create assignment for approved application
- [ ] TC-SR-020: Assign dosen as 'pembimbing'
- [ ] TC-SR-021: Assign dosen as 'reviewer'
- [ ] TC-SR-022: Set assignment date
- [ ] TC-SR-023: Save assignment
- [ ] TC-SR-024: Verify assignment appears in dosen dashboard

**Expected Result:**
- Assignment created successfully
- Dosen receives task notification
- Assignment visible in `/dosen/task-assignments`

---

#### Stage 4: Persetujuan Pembimbing
**Page:** `/dosen/task-assignments`
**Actor:** Dosen

**Test Cases:**
- [ ] TC-SR-025: View assigned tasks
- [ ] TC-SR-026: Click 'Review' button
- [ ] TC-SR-027: Navigate to review proposal page
- [ ] TC-SR-028: View mahasiswa information
- [ ] TC-SR-029: View proposal documents
- [ ] TC-SR-030: Select decision (approved/revision/rejected)
- [ ] TC-SR-031: Enter score (0-100)
- [ ] TC-SR-032: Enter feedback (required)
- [ ] TC-SR-033: Enter revision notes (if revision selected)
- [ ] TC-SR-034: Submit review
- [ ] TC-SR-035: Verify application status updates

**Expected Result:**
- Review submitted successfully
- Application status changes based on decision
- Mahasiswa can see feedback

---

#### Stage 5: Penyusunan & Bimbingan
**Note:** This is an offline process, no form testing needed
**Status:** Manual tracking by dosen

---

#### Stage 6: Pendaftaran Seminar Proposal
**Form:** `/frontend/skripsi-seminars/create`
**Actor:** Mahasiswa

**Test Cases:**
- [ ] TC-SR-036: Access seminar registration form
- [ ] TC-SR-037: Check form access control (only after stage 5)
- [ ] TC-SR-038: Fill title field
- [ ] TC-SR-039: Fill description/abstract
- [ ] TC-SR-040: Upload proposal document (PDF, required, max 10MB)
- [ ] TC-SR-041: Upload approval document (PDF, required, max 10MB)
- [ ] TC-SR-042: Upload plagiarism check (PDF, required, max 10MB)
- [ ] TC-SR-043: Enter notes (optional)
- [ ] TC-SR-044: Submit registration
- [ ] TC-SR-045: Validation: all required files must be uploaded
- [ ] TC-SR-046: View registered seminar in list

**Expected Result:**
- Seminar registration created
- New application stage = 'seminar'
- Status = 'submitted'

---

#### Stage 7: Verifikasi Seminar (Admin)
**Page:** `/admin/skripsi-seminars`
**Actor:** Administrator

**Test Cases:**
- [ ] TC-SR-047: View pending seminar registrations
- [ ] TC-SR-048: Open seminar detail
- [ ] TC-SR-049: Download and verify documents
- [ ] TC-SR-050: Approve seminar registration
- [ ] TC-SR-051: Reject with notes
- [ ] TC-SR-052: Verify status changes

**Expected Result:**
- Admin can verify seminar
- Status updates correctly

---

#### Stage 8: Penjadwalan Seminar
**Form:** `/frontend/application-schedules/create`
**Actor:** Mahasiswa (or Admin)

**Test Cases:**
- [ ] TC-SR-053: Access schedule creation form
- [ ] TC-SR-054: Select schedule type (skripsi_seminar)
- [ ] TC-SR-055: Select date (future date required)
- [ ] TC-SR-056: Select time
- [ ] TC-SR-057: Choose location type (offline/online)
- [ ] TC-SR-058: Select room (if offline)
- [ ] TC-SR-059: Enter meeting link (if online)
- [ ] TC-SR-060: Enter notes
- [ ] TC-SR-061: Submit schedule
- [ ] TC-SR-062: View schedule in list

**Admin Verification:**
- [ ] TC-SR-063: Admin views schedule in `/admin/application-schedules`
- [ ] TC-SR-064: Admin approves schedule
- [ ] TC-SR-065: Admin rejects schedule
- [ ] TC-SR-066: Application status changes to 'scheduled' after approval

**Expected Result:**
- Schedule created successfully
- Admin can verify
- Status changes to 'scheduled'

---

#### Stage 9: Pelaksanaan Seminar & Hasil Review
**Form:** `/frontend/application-result-seminars/create`
**Actor:** Mahasiswa (reporting results)

**Test Cases:**
- [ ] TC-SR-067: Access result seminar form
- [ ] TC-SR-068: Select result (passed/revision/failed)
- [ ] TC-SR-069: Set revision deadline (if revision)
- [ ] TC-SR-070: Enter reviewer notes
- [ ] TC-SR-071: Upload report documents (BA) - multiple files
- [ ] TC-SR-072: Upload attendance document
- [ ] TC-SR-073: Upload assessment forms - multiple files
- [ ] TC-SR-074: Submit result
- [ ] TC-SR-075: View result in detail page

**Expected Result:**
- Result recorded successfully
- Documents uploaded
- Status updates based on result

---

#### Stage 10: Pendaftaran Sidang
**Form:** `/frontend/skripsi-defenses/create`
**Actor:** Mahasiswa

**Test Cases:**
- [ ] TC-SR-076: Access defense registration form
- [ ] TC-SR-077: Check access control (only after seminar passed)
- [ ] TC-SR-078: Enter thesis title
- [ ] TC-SR-079: Enter abstract (required)
- [ ] TC-SR-080: Upload final thesis document (PDF, max 20MB)
- [ ] TC-SR-081: Upload approval document (PDF, max 10MB)
- [ ] TC-SR-082: Upload plagiarism check final (PDF, max 10MB)
- [ ] TC-SR-083: Upload revision proof (PDF, optional)
- [ ] TC-SR-084: Enter notes
- [ ] TC-SR-085: Submit defense registration

**Admin Verification:**
- [ ] TC-SR-086: Admin views in `/admin/skripsi-defenses`
- [ ] TC-SR-087: Admin approves defense
- [ ] TC-SR-088: Admin rejects defense

**Schedule Defense:**
- [ ] TC-SR-089: Create schedule for defense (similar to seminar)
- [ ] TC-SR-090: Admin verifies schedule

**Dosen Scoring:**
- [ ] TC-SR-091: Dosen accesses `/dosen/scoring/{application}`
- [ ] TC-SR-092: Enter overall score (0-100)
- [ ] TC-SR-093: Enter component scores (presentation, content, methodology, Q&A)
- [ ] TC-SR-094: Select grade letter (A, B+, B, C+, C, D)
- [ ] TC-SR-095: Enter comments (required)
- [ ] TC-SR-096: Select recommendation (passed/passed_with_revision/failed)
- [ ] TC-SR-097: Submit scoring

**Report Defense Result:**
- [ ] TC-SR-098: Mahasiswa reports defense result
- [ ] TC-SR-099: Upload final documents
- [ ] TC-SR-100: Submit result

**Expected Result:**
- Defense registered successfully
- Scoring completed
- Final result recorded

---

### B. SKRIPSI MBKM WORKFLOW

#### Stage 1: Pendaftaran MBKM
**Form:** `/frontend/mbkm/create`
**Actor:** Mahasiswa

**Test Cases:**
- [ ] TC-MBKM-001: Access MBKM registration form
- [ ] TC-MBKM-002: Fill student information
- [ ] TC-MBKM-003: Enter MBKM program details
- [ ] TC-MBKM-004: Upload MBKM certificate
- [ ] TC-MBKM-005: Upload KHS
- [ ] TC-MBKM-006: Upload KRS
- [ ] TC-MBKM-007: Select research group
- [ ] TC-MBKM-008: Submit registration
- [ ] TC-MBKM-009: Verify wizard navigation works

**Expected Result:**
- MBKM registration created
- Type = 'mbkm'
- Status = 'submitted'

---

#### Stage 2-4: Verifikasi & Penugasan (Similar to Skripsi)
**Test Cases:**
- [ ] TC-MBKM-010: Admin verifies MBKM registration
- [ ] TC-MBKM-011: Admin assigns pembimbing
- [ ] TC-MBKM-012: Dosen reviews MBKM proposal
- [ ] TC-MBKM-013: Dosen provides feedback

---

#### Stage 5: Konversi SKS MBKM (Admin)
**Note:** This is admin data entry, no specific form
- [ ] TC-MBKM-014: Admin records SKS conversion

---

#### Stage 6: Pendaftaran Seminar MBKM
**Form:** `/frontend/mbkm-seminars/create`
**Actor:** Mahasiswa

**Test Cases:**
- [ ] TC-MBKM-015: Access MBKM seminar form
- [ ] TC-MBKM-016: Fill MBKM title
- [ ] TC-MBKM-017: Fill description
- [ ] TC-MBKM-018: Upload proposal document (required)
- [ ] TC-MBKM-019: Upload approval document (required)
- [ ] TC-MBKM-020: Upload plagiarism check (required)
- [ ] TC-MBKM-021: Submit MBKM seminar
- [ ] TC-MBKM-022: View in list

**Expected Result:**
- MBKM seminar registered
- All documents uploaded
- Status = 'submitted'

---

#### Stage 7-11: Remaining MBKM stages (Similar to Skripsi)
**Test Cases:**
- [ ] TC-MBKM-023: Admin verifies MBKM seminar
- [ ] TC-MBKM-024: Schedule MBKM seminar
- [ ] TC-MBKM-025: Report MBKM seminar result
- [ ] TC-MBKM-026: Register MBKM defense (uses same SkripsiDefense)
- [ ] TC-MBKM-027: Schedule MBKM defense
- [ ] TC-MBKM-028: Dosen scores MBKM defense
- [ ] TC-MBKM-029: Report final MBKM result

---

### C. CROSS-CUTTING FEATURES

#### Timeline Component
**Component:** `components/thesis-timeline.blade.php`

**Test Cases:**
- [ ] TC-TL-001: Timeline displays for Skripsi Reguler (10 stages)
- [ ] TC-TL-002: Timeline displays for MBKM (11 stages)
- [ ] TC-TL-003: Current stage highlighted correctly
- [ ] TC-TL-004: Completed stages show green checkmark
- [ ] TC-TL-005: Pending stages show gray
- [ ] TC-TL-006: Status badges display correctly
- [ ] TC-TL-007: Compact mode works in dosen modal
- [ ] TC-TL-008: Vertical mode works in detail pages

---

#### Application Report (Issue Reporting)
**Form:** `/frontend/application-reports/create`

**Test Cases:**
- [ ] TC-REP-001: Create issue report
- [ ] TC-REP-002: Set priority (low/medium/high)
- [ ] TC-REP-003: Enter description
- [ ] TC-REP-004: Upload evidence documents
- [ ] TC-REP-005: Submit report
- [ ] TC-REP-006: View report status
- [ ] TC-REP-007: Admin views reports
- [ ] TC-REP-008: Admin responds to reports

---

#### Admin Dashboard & Monitoring
**Pages:** `/admin`, `/admin/monitoring`

**Test Cases:**
- [ ] TC-ADM-001: View dashboard statistics
- [ ] TC-ADM-002: Verify pending verification count
- [ ] TC-ADM-003: View recent applications
- [ ] TC-ADM-004: Access quick actions
- [ ] TC-ADM-005: Open monitoring dashboard
- [ ] TC-ADM-006: View all applications with timeline
- [ ] TC-ADM-007: Filter by type (skripsi/mbkm)
- [ ] TC-ADM-008: Filter by status
- [ ] TC-ADM-009: Pagination works

---

#### Dosen Dashboard
**Page:** `/dosen`

**Test Cases:**
- [ ] TC-DOS-001: View statistics (mahasiswa bimbingan, assignments, etc.)
- [ ] TC-DOS-002: View recent assignments
- [ ] TC-DOS-003: Navigate to mahasiswa bimbingan page
- [ ] TC-DOS-004: View timeline modal for each student
- [ ] TC-DOS-005: Navigate to task assignments
- [ ] TC-DOS-006: Navigate to scores page

---

## 🔒 Security & Permission Testing

**Test Cases:**
- [ ] TC-SEC-001: Mahasiswa cannot access admin pages
- [ ] TC-SEC-002: Mahasiswa cannot access dosen pages
- [ ] TC-SEC-003: Dosen cannot access admin pages
- [ ] TC-SEC-004: Dosen cannot access other dosen's assignments
- [ ] TC-SEC-005: Mahasiswa can only see their own applications
- [ ] TC-SEC-006: File upload CSRF protection works
- [ ] TC-SEC-007: SQL injection prevention (Eloquent)

---

## 📱 Responsive & Browser Testing

**Test Cases:**
- [ ] TC-RES-001: Desktop view (1920x1080)
- [ ] TC-RES-002: Laptop view (1366x768)
- [ ] TC-RES-003: Tablet view (768x1024)
- [ ] TC-RES-004: Mobile view (375x667)
- [ ] TC-BRW-001: Chrome (latest)
- [ ] TC-BRW-002: Firefox (latest)
- [ ] TC-BRW-003: Edge (latest)
- [ ] TC-BRW-004: Safari (latest)

---

## 📊 Performance Testing

**Test Cases:**
- [ ] TC-PERF-001: Page load time < 3 seconds
- [ ] TC-PERF-002: File upload < 30 seconds for 10MB
- [ ] TC-PERF-003: List page with 100 records loads smoothly
- [ ] TC-PERF-004: Select2 dropdown responds instantly
- [ ] TC-PERF-005: Modal opens/closes smoothly

---

## 🐛 Known Issues to Test

Based on previous fixes:
- [ ] TC-FIX-001: Modal glitch on dosen/task-assignments (FIXED - verify)
- [ ] TC-FIX-002: Select2 dropdown clipping (FIXED - verify)
- [ ] TC-FIX-003: Wizard form navigation (FIXED - verify)
- [ ] TC-FIX-004: Carbon date parsing errors (FIXED - verify)
- [ ] TC-FIX-005: File upload validation (FIXED - verify)

---

## ✅ Test Execution Checklist

### Pre-Testing Setup:
- [ ] Database seeded with test data
- [ ] Storage symlink configured
- [ ] File upload limits configured
- [ ] Test accounts created (admin, dosen, mahasiswa)

### Test Environment:
- [ ] Development environment
- [ ] Staging environment (if available)
- [ ] Production-like data

### Test Data:
- [ ] 3 Mahasiswa accounts
- [ ] 3 Dosen accounts
- [ ] 1 Admin account
- [ ] Sample PDF files (various sizes: 1MB, 5MB, 10MB, 15MB)

---

## 📝 Test Reporting

**For each failed test case, record:**
1. Test Case ID
2. Expected Result
3. Actual Result
4. Steps to Reproduce
5. Screenshots/Logs
6. Priority (Critical/High/Medium/Low)

---

**Total Test Cases: 150+**
**Estimated Testing Time: 4-6 hours**
**Target: 95%+ pass rate**

---

**Next Step:** Execute testing systematically, starting with Skripsi Reguler workflow end-to-end.

