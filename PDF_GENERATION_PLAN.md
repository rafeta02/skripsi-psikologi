# 📄 PDF Report Generation - Implementation Plan

**Date:** 2026-05-09  
**Purpose:** Generate official PDF documents for thesis management system

---

## 🎯 Documents That Need PDF Generation

### 1. Admin Documents
- ✅ **Surat Tugas Pembimbing** - Assignment letter for supervisor
- ✅ **Surat Tugas Reviewer** - Assignment letter for reviewer
- ✅ **Surat Tugas Penguji** - Assignment letter for examiner
- ✅ **Lembar Persetujuan Seminar** - Seminar approval form
- ✅ **Lembar Persetujuan Sidang** - Defense approval form

### 2. Mahasiswa Documents
- ✅ **Kartu Bimbingan** - Supervision log card
- ✅ **Formulir Pendaftaran Seminar** - Seminar registration form
- ✅ **Formulir Pendaftaran Sidang** - Defense registration form
- ✅ **Bukti Pendaftaran** - Registration receipt

### 3. Dosen Documents
- ✅ **Lembar Penilaian Proposal** - Proposal assessment form
- ✅ **Lembar Penilaian Seminar** - Seminar assessment form
- ✅ **Lembar Penilaian Sidang** - Defense assessment form
- ✅ **Berita Acara Review** - Review meeting minutes

### 4. Official Reports
- ✅ **Berita Acara Seminar** - Seminar minutes (BA Seminar)
- ✅ **Berita Acara Sidang** - Defense minutes (BA Sidang)
- ✅ **Surat Keterangan Lulus** - Certificate of completion
- ✅ **Transkrip Nilai Skripsi** - Thesis grade transcript

---

## 🛠️ Implementation Strategy

### Technology Stack
- **Library:** Laravel DomPDF (barasaheb/laravel-dompdf)
- **Alternative:** Laravel Snappy (for advanced layouts)
- **Templates:** Blade views with PDF-specific CSS
- **Fonts:** Embedded fonts for consistency

### Architecture
```
app/
├── Services/
│   └── PdfGeneratorService.php      # Main PDF service
├── Http/Controllers/
│   ├── Admin/PdfController.php      # Admin PDF endpoints
│   ├── Dosen/PdfController.php      # Dosen PDF endpoints
│   └── Frontend/PdfController.php   # Mahasiswa PDF endpoints
resources/views/pdf/
├── layouts/
│   ├── base.blade.php               # Base layout with header/footer
│   └── official.blade.php           # Official document layout
├── admin/
│   ├── surat-tugas.blade.php
│   ├── lembar-persetujuan.blade.php
│   └── ...
├── dosen/
│   ├── lembar-penilaian.blade.php
│   ├── berita-acara-review.blade.php
│   └── ...
├── mahasiswa/
│   ├── kartu-bimbingan.blade.php
│   ├── formulir-pendaftaran.blade.php
│   └── ...
└── reports/
    ├── berita-acara-seminar.blade.php
    ├── berita-acara-sidang.blade.php
    └── ...
```

---

## 📋 Priority Implementation

### Phase 1: Essential Documents (High Priority)
1. **Surat Tugas Pembimbing/Reviewer/Penguji**
2. **Berita Acara Seminar**
3. **Berita Acara Sidang**
4. **Lembar Penilaian**

### Phase 2: Supporting Documents (Medium Priority)
5. **Kartu Bimbingan**
6. **Formulir Pendaftaran**
7. **Lembar Persetujuan**

### Phase 3: Administrative Reports (Low Priority)
8. **Transkrip Nilai**
9. **Surat Keterangan Lulus**
10. **Monitoring Reports**

---

## 🎨 PDF Design Requirements

### Official Document Standards
- **Paper Size:** A4 (210mm x 297mm)
- **Margins:** 2.5cm top/bottom, 3cm left/right
- **Font:** Times New Roman, 12pt body, 14pt headings
- **Header:** University logo + name
- **Footer:** Signature areas + date

### Layout Elements
- University letterhead
- Document number
- Date
- Title (centered, bold)
- Body content (justified)
- Signature blocks (3-4 columns)
- Official stamp area
- Page numbers (if multi-page)

---

## 🔧 Technical Implementation

### 1. Install DomPDF
```bash
composer require barryvdh/laravel-dompdf
```

### 2. Configuration
```php
// config/dompdf.php
'defines' => [
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => sys_get_temp_dir(),
    'chroot' => realpath(base_path()),
    'enable_font_subsetting' => false,
    'pdf_backend' => 'CPDF',
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_font' => 'serif',
    'dpi' => 96,
    'enable_php' => false,
    'enable_javascript' => true,
    'enable_remote' => true,
    'font_height_ratio' => 1.1,
    'enable_html5_parser' => true,
]
```

### 3. Service Class Structure
```php
class PdfGeneratorService
{
    public function generateSuratTugas($assignment): Pdf;
    public function generateBeritaAcaraSeminar($seminar): Pdf;
    public function generateBeritaAcaraSidang($defense): Pdf;
    public function generateLembarPenilaian($score): Pdf;
    public function generateKartuBimbingan($mahasiswa): Pdf;
    public function generateFormulirPendaftaran($application): Pdf;
    
    // Helpers
    private function loadView(string $view, array $data): string;
    private function addOfficialHeader(array $data): array;
    private function addSignatureBlock(array $signers): string;
    private function formatDate($date): string;
    private function generateDocumentNumber(): string;
}
```

---

## 📄 Document Specifications

### 1. Surat Tugas (Assignment Letter)

**Data Required:**
- Assignment ID & Date
- Mahasiswa: Name, NIM, Prodi
- Dosen: Name, NIP/NIDN, Position
- Role: Pembimbing/Reviewer/Penguji
- Application Type: Skripsi Reguler/MBKM
- Thesis Title

**Signature Block:**
- Kaprodi (left)
- Official Stamp (center)
- Dean/Vice Dean (right)

---

### 2. Berita Acara Seminar (Seminar Minutes)

**Data Required:**
- Document Number & Date
- Mahasiswa: Name, NIM, Prodi
- Seminar Date, Time, Location
- Proposal Title
- Pembimbing: Name, NIP
- Reviewers: Name, NIP (2 people)
- Attendees: List
- Result: Passed/Revision/Failed
- Notes/Comments
- Revision Deadline (if applicable)

**Signature Block:**
- Mahasiswa (left)
- Pembimbing (center-left)
- Reviewer 1 (center-right)
- Reviewer 2 (right)

---

### 3. Berita Acara Sidang (Defense Minutes)

**Data Required:**
- Document Number & Date
- Mahasiswa: Name, NIM, Prodi
- Defense Date, Time, Location
- Thesis Title
- Pembimbing: Name, NIP
- Examiners: Name, NIP (2 people)
- Attendees: List
- Result: Passed/Passed with Revision/Failed
- Final Grade & Letter Grade
- Notes/Comments
- Revision Deadline (if applicable)

**Signature Block:**
- Mahasiswa (left)
- Pembimbing (center-left)
- Examiner 1 (center-right)
- Examiner 2 (right)

---

### 4. Lembar Penilaian (Assessment Form)

**Data Required:**
- Mahasiswa: Name, NIM, Prodi
- Thesis Title
- Assessment Type: Proposal/Seminar/Defense
- Assessor: Name, NIP, Role
- Assessment Criteria & Scores:
  - Content/Substance (30%)
  - Methodology (25%)
  - Presentation (25%)
  - Q&A (20%)
- Total Score (0-100)
- Grade Letter (A, B+, B, C+, C, D)
- Comments/Feedback
- Recommendation
- Date

**Signature Block:**
- Assessor signature & stamp

---

### 5. Kartu Bimbingan (Supervision Card)

**Data Required:**
- Mahasiswa: Name, NIM, Prodi
- Thesis Title
- Pembimbing: Name, NIP
- Guidance Sessions (table):
  - Date
  - Topic Discussed
  - Notes/Feedback
  - Signature
- Total Sessions

**Format:** Table with multiple rows for sessions

---

### 6. Formulir Pendaftaran (Registration Form)

**Data Required:**
- Form Type: Seminar/Defense
- Registration Number & Date
- Mahasiswa: Name, NIM, Prodi, Email, Phone
- Thesis Title
- Abstract (short)
- Pembimbing: Name, NIP
- Uploaded Documents Checklist:
  - ☑ Proposal
  - ☑ Approval Letter
  - ☑ Plagiarism Check
  - ☑ KHS
  - ☑ etc.
- Declaration statement
- Signature area

---

## 🔐 Security & Access Control

### Download Permissions
- **Mahasiswa:** Can download own documents
- **Dosen:** Can download documents for assigned students
- **Admin:** Can download all documents

### Watermarks
- Draft documents: "DRAFT" watermark
- Final documents: No watermark
- Rejected documents: "REJECTED" watermark

### Document Numbering
- Format: `[TYPE]/[YEAR]/[PRODI]/[SEQUENCE]`
- Example: `ST/2026/PSI/001` (Surat Tugas)
- Example: `BAS/2026/PSI/015` (Berita Acara Seminar)
- Example: `BAD/2026/PSI/023` (Berita Acara Sidang)

---

## 🚀 Implementation Steps

### Step 1: Install & Configure
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Step 2: Create Service
```bash
php artisan make:service PdfGeneratorService
```

### Step 3: Create Controllers
```bash
php artisan make:controller Admin/PdfController
php artisan make:controller Dosen/PdfController
php artisan make:controller Frontend/PdfController
```

### Step 4: Create PDF Views
- Create `resources/views/pdf/` directory
- Create layout templates
- Create document templates

### Step 5: Add Routes
```php
Route::group(['prefix' => 'pdf', 'as' => 'pdf.'], function () {
    // Admin routes
    Route::get('surat-tugas/{assignment}', 'PdfController@suratTugas')->name('surat-tugas');
    Route::get('berita-acara-seminar/{seminar}', 'PdfController@beritaAcaraSeminar')->name('ba-seminar');
    Route::get('berita-acara-sidang/{defense}', 'PdfController@beritaAcaraSidang')->name('ba-sidang');
    // ... more routes
});
```

### Step 6: Add Download Buttons to Views
- Admin: Add "Download PDF" buttons
- Dosen: Add "Download PDF" buttons
- Mahasiswa: Add "Download PDF" buttons

---

## 📊 Expected Output

### File Naming Convention
- `Surat_Tugas_[NIM]_[Dosen]_[Date].pdf`
- `BA_Seminar_[NIM]_[Name]_[Date].pdf`
- `BA_Sidang_[NIM]_[Name]_[Date].pdf`
- `Lembar_Penilaian_[Type]_[NIM]_[Date].pdf`
- `Kartu_Bimbingan_[NIM]_[Name].pdf`

### Download Locations in UI
1. **Admin Panel:**
   - Application Assignments → "Download Surat Tugas"
   - Skripsi Seminars → "Download BA Seminar"
   - Skripsi Defenses → "Download BA Sidang"

2. **Dosen Panel:**
   - Task Assignments → "Download Surat Tugas"
   - Mahasiswa Bimbingan → "Download Kartu Bimbingan"
   - Scores → "Download Lembar Penilaian"

3. **Mahasiswa Panel:**
   - Aplikasi → "Download Formulir Pendaftaran"
   - Seminar → "Download BA Seminar"
   - Sidang → "Download BA Sidang"
   - Dashboard → "Download Kartu Bimbingan"

---

## ✅ Testing Checklist

- [ ] PDF generates without errors
- [ ] Fonts render correctly (Times New Roman)
- [ ] University logo displays
- [ ] All data fields populated
- [ ] Signature blocks aligned
- [ ] Page breaks work correctly
- [ ] File downloads with correct name
- [ ] Permission checks work
- [ ] Document numbers unique
- [ ] Watermarks display correctly
- [ ] Multi-page documents work
- [ ] Print layout correct (A4)

---

## 🎨 Styling Guidelines

### CSS for PDF
```css
@page {
    margin: 2.5cm 3cm;
}

body {
    font-family: 'Times New Roman', serif;
    font-size: 12pt;
    line-height: 1.5;
}

.header {
    text-align: center;
    margin-bottom: 2cm;
}

.title {
    font-size: 14pt;
    font-weight: bold;
    text-align: center;
    margin: 1cm 0;
}

.signature-block {
    display: table;
    width: 100%;
    margin-top: 2cm;
}

.signature-cell {
    display: table-cell;
    width: 25%;
    text-align: center;
    vertical-align: top;
}
```

---

## 🔄 Next Steps

1. ✅ Install DomPDF package
2. ✅ Create PdfGeneratorService
3. ✅ Create PDF view templates
4. ✅ Create controllers & routes
5. ✅ Add download buttons to UI
6. ✅ Test all PDF documents
7. ✅ Add watermarks & document numbers
8. ✅ Implement access control
9. ✅ Update documentation

---

**Status:** 📋 **READY TO IMPLEMENT**

**Estimated Time:** 6-8 hours for Phase 1 (essential documents)

**Priority:** High - Required for official documentation
