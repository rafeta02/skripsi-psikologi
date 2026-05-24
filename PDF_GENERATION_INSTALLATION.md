# 📄 PDF Generation - Installation & Usage Guide

**Date:** 2026-05-09  
**Status:** ✅ Code Complete - Pending Package Installation

---

## 🚨 IMPORTANT: Installation Requirements

### Step 1: Enable PHP ZIP Extension

The DomPDF package requires PHP `ext-zip` extension. Enable it first:

**Windows (XAMPP/WAMP):**
1. Open `C:\xampp\php\php.ini`
2. Find line: `;extension=zip`
3. Remove the semicolon: `extension=zip`
4. Restart Apache
5. Verify: `php -m | findstr zip`

**Linux/Mac:**
```bash
# Ubuntu/Debian
sudo apt-get install php-zip

# CentOS/RHEL
sudo yum install php-zip

# macOS (Homebrew)
brew install php@8.2-zip

# Verify
php -m | grep zip
```

---

### Step 2: Install DomPDF Package

```bash
composer require barryvdh/laravel-dompdf
```

---

### Step 3: Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

This creates `config/dompdf.php` for customization.

---

## 📁 What Has Been Created

### 1. Service Class
**File:** `app/Services/PdfGeneratorService.php`

**Methods:**
- `generateSuratTugas()` - Assignment letter
- `generateBeritaAcaraSeminar()` - Seminar minutes  
- `generateBeritaAcaraSidang()` - Defense minutes
- `generateLembarPenilaian()` - Assessment form
- `generateKartuBimbingan()` - Supervision card
- `generateFormulirPendaftaranSeminar()` - Seminar registration form
- `generateFormulirPendaftaranSidang()` - Defense registration form

---

### 2. Controller
**File:** `app/Http/Controllers/PdfController.php`

**Endpoints:**
- `GET /pdf/surat-tugas/{assignment}` - Download assignment letter
- `GET /pdf/berita-acara-seminar/{seminar}` - Download seminar minutes
- `GET /pdf/berita-acara-sidang/{defense}` - Download defense minutes
- `GET /pdf/lembar-penilaian/{score}` - Download assessment form
- `GET /pdf/kartu-bimbingan/{application}` - Download supervision card
- `GET /pdf/formulir-seminar/{seminar}` - Download seminar registration
- `GET /pdf/formulir-sidang/{defense}` - Download defense registration
- `GET /pdf/preview?type=xxx&id=xxx` - Preview PDF in browser (testing)

---

### 3. PDF Templates
**Directory:** `resources/views/pdf/`

**Created Templates:**
- `layouts/base.blade.php` - Base layout with university header/footer
- `admin/surat-tugas.blade.php` - Assignment letter template
- `reports/berita-acara-seminar.blade.php` - Seminar minutes template
- `reports/berita-acara-sidang.blade.php` - Defense minutes template
- `dosen/lembar-penilaian.blade.php` - Assessment form template

**TODO (Optional):**
- `mahasiswa/kartu-bimbingan.blade.php` - Supervision card
- `mahasiswa/formulir-pendaftaran-seminar.blade.php` - Seminar form
- `mahasiswa/formulir-pendaftaran-sidang.blade.php` - Defense form

---

### 4. Routes
**File:** `routes/web.php`

All PDF routes added under `/pdf` prefix with authentication middleware.

---

## 🚀 How to Use

### In Controllers

```php
use App\Services\PdfGeneratorService;

class YourController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function downloadSuratTugas($assignmentId)
    {
        $assignment = ApplicationAssignment::findOrFail($assignmentId);
        
        $pdf = $this->pdfService->generateSuratTugas($assignment);
        $filename = $this->pdfService->generateFilename('surat-tugas', $assignment);
        
        return $pdf->download($filename);
    }
}
```

---

### In Blade Views (Add Download Buttons)

#### Admin - Application Assignments Page
```blade
{{-- In resources/views/admin/application-assignments/index.blade.php --}}
<a href="{{ route('pdf.surat-tugas', $assignment->id) }}" 
   class="btn btn-sm btn-success" 
   target="_blank">
    <i class="fas fa-file-pdf"></i> Download Surat Tugas
</a>
```

#### Mahasiswa - Seminar Page
```blade
{{-- In resources/views/frontend/skripsiSeminars/show.blade.php --}}
<a href="{{ route('pdf.ba-seminar', $seminar->id) }}" 
   class="btn btn-primary" 
   target="_blank">
    <i class="fas fa-download"></i> Download Berita Acara Seminar
</a>

<a href="{{ route('pdf.formulir-seminar', $seminar->id) }}" 
   class="btn btn-secondary" 
   target="_blank">
    <i class="fas fa-file-alt"></i> Download Formulir Pendaftaran
</a>
```

#### Dosen - Scores Page
```blade
{{-- In resources/views/dosen/scores.blade.php --}}
<a href="{{ route('pdf.lembar-penilaian', $score->id) }}" 
   class="btn btn-sm btn-info" 
   target="_blank">
    <i class="fas fa-certificate"></i> Download Lembar Penilaian
</a>
```

---

## 🎨 Customization

### Modify University Header

Edit `resources/views/pdf/layouts/base.blade.php`:

```blade
<div class="header">
    <img src="{{ public_path('images/logo-universitas.png') }}" alt="Logo" class="header-logo">
    <div class="header-title">UNIVERSITAS NEGERI JAKARTA</div>
    <div class="header-subtitle">FAKULTAS PSIKOLOGI</div>
    <div class="header-subtitle">PROGRAM STUDI PSIKOLOGI</div>
    <div class="header-address">
        Jl. Rawamangun Muka, Jakarta Timur 13220 | Telp: (021) 4893854 | Email: psikologi@unj.ac.id
    </div>
</div>
```

---

### Add University Logo

1. Place logo image: `public/images/logo-universitas.png`
2. Update base layout to reference it
3. Make sure logo is 80x80px or proportional

---

### Customize PDF Settings

Edit `config/dompdf.php` (after publishing):

```php
return [
    'show_warnings' => false,
    'public_path' => public_path(),
    'convert_entities' => true,
    'options' => [
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
    ],
];
```

---

## 📋 Testing

### Test Individual PDFs

```bash
# Preview in browser
http://localhost:8000/pdf/preview?type=surat-tugas&id=1

# Download directly
http://localhost:8000/pdf/surat-tugas/1
http://localhost:8000/pdf/ba-seminar/1
http://localhost:8000/pdf/ba-sidang/1
```

---

## 🐛 Troubleshooting

### Issue: "Barryvdh\DomPDF\Facade\Pdf not found"
**Solution:** Run `composer dump-autoload`

### Issue: "Failed to load external entity"
**Solution:** Ensure images use `public_path()` not `asset()` in PDFs

### Issue: "Font not found"
**Solution:** Use web-safe fonts: Times New Roman, Arial, Helvetica

### Issue: "PDF layout broken"
**Solution:** Avoid CSS Grid, Flexbox. Use tables for layout.

### Issue: "Signature blocks not aligned"
**Solution:** Use `float: left/right` and `clear: both`

---

## 📊 Document Specifications

### Surat Tugas (Assignment Letter)
- **Used by:** Admin
- **Purpose:** Assign dosen as pembimbing/reviewer/examiner
- **Signature:** Kaprodi + official stamp
- **Recipients:** Dosen, mahasiswa (copy)

### Berita Acara Seminar (Seminar Minutes)
- **Used by:** Admin, Mahasiswa, Dosen
- **Purpose:** Official record of proposal seminar
- **Signatures:** Mahasiswa, Pembimbing, 2 Reviewers
- **Content:** Seminar details, result, feedback

### Berita Acara Sidang (Defense Minutes)
- **Used by:** Admin, Mahasiswa, Dosen
- **Purpose:** Official record of thesis defense
- **Signatures:** Mahasiswa, Pembimbing, 2 Examiners, Kaprodi
- **Content:** Defense details, scores, final grade, result

### Lembar Penilaian (Assessment Form)
- **Used by:** Dosen
- **Purpose:** Record assessment scores
- **Signature:** Assessor (Dosen)
- **Content:** Detailed scores by criteria, total, comments

---

## ✅ Next Steps

1. **Enable ext-zip:** Follow Step 1 above
2. **Install DomPDF:** `composer require barryvdh/laravel-dompdf`
3. **Test PDF generation:** Visit `/pdf/preview?type=surat-tugas&id=1`
4. **Add download buttons:** Update views with PDF links
5. **Customize header:** Update university name, logo, address
6. **Deploy:** Ensure production server has ext-zip enabled

---

## 📚 Additional Resources

- **DomPDF Documentation:** https://github.com/barryvdh/laravel-dompdf
- **DomPDF GitHub:** https://github.com/dompdf/dompdf
- **CSS for Print:** https://www.smashingmagazine.com/2015/01/designing-for-print-with-css/

---

## 🎯 Summary

✅ **Service Class:** Created  
✅ **Controller:** Created  
✅ **Routes:** Added  
✅ **Templates:** 4 documents ready  
⏳ **Package Installation:** Pending (ext-zip + composer)  
⏳ **UI Integration:** Pending (add download buttons)  
⏳ **Customization:** Pending (university details)  

---

**Status:** 📋 **Ready for Installation**  
**Next:** Enable ext-zip → Install DomPDF → Test PDFs

---

*Generated: 2026-05-09*
