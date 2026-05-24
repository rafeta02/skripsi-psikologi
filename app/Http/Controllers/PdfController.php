<?php

namespace App\Http\Controllers;

use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Application;
use App\Models\SkripsiSeminar;
use App\Models\MbkmSeminar;
use App\Models\SkripsiDefense;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationResultDefense;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Download Surat Tugas (Assignment Letter)
     */
    public function suratTugas(ApplicationAssignment $assignment)
    {
        // Authorization check
        $this->authorize('view', $assignment);

        $pdf = $this->pdfService->generateSuratTugas($assignment);
        $filename = $this->pdfService->generateFilename('surat-tugas', $assignment);

        return $pdf->download($filename);
    }

    /**
     * Download Berita Acara Seminar
     */
    public function beritaAcaraSeminar($seminarId)
    {
        // Try both SkripsiSeminar and MbkmSeminar
        $seminar = SkripsiSeminar::find($seminarId) ?? MbkmSeminar::find($seminarId);
        
        if (!$seminar) {
            abort(404, 'Seminar not found');
        }

        // Authorization check
        $this->authorize('view', $seminar->application);

        // Get result if exists
        $result = ApplicationResultSeminar::where('application_id', $seminar->application_id)->first();

        $pdf = $this->pdfService->generateBeritaAcaraSeminar($seminar, $result);
        $filename = $this->pdfService->generateFilename('ba-seminar', $seminar);

        return $pdf->download($filename);
    }

    /**
     * Download Berita Acara Sidang
     */
    public function beritaAcaraSidang(SkripsiDefense $defense)
    {
        // Authorization check
        $this->authorize('view', $defense->application);

        // Get result if exists
        $result = ApplicationResultDefense::where('application_id', $defense->application_id)->first();

        $pdf = $this->pdfService->generateBeritaAcaraSidang($defense, $result);
        $filename = $this->pdfService->generateFilename('ba-sidang', $defense);

        return $pdf->download($filename);
    }

    /**
     * Download Lembar Penilaian
     */
    public function lembarPenilaian(ApplicationScore $score)
    {
        // Authorization check
        $user = Auth::user();
        if (!$user->is_admin && $score->examiner_id != $user->dosen_id) {
            abort(403, 'Unauthorized');
        }

        $pdf = $this->pdfService->generateLembarPenilaian($score);
        $filename = $this->pdfService->generateFilename('lembar-penilaian', $score);

        return $pdf->download($filename);
    }

    /**
     * Download Kartu Bimbingan
     */
    public function kartuBimbingan(Application $application)
    {
        // Authorization check
        $this->authorize('view', $application);

        $pdf = $this->pdfService->generateKartuBimbingan($application);
        $filename = $this->pdfService->generateFilename('kartu-bimbingan', $application);

        return $pdf->download($filename);
    }

    /**
     * Download Formulir Pendaftaran Seminar
     */
    public function formulirSeminar($seminarId)
    {
        $seminar = SkripsiSeminar::find($seminarId) ?? MbkmSeminar::find($seminarId);
        
        if (!$seminar) {
            abort(404, 'Seminar not found');
        }

        // Authorization check
        $this->authorize('view', $seminar->application);

        $pdf = $this->pdfService->generateFormulirPendaftaranSeminar($seminar);
        $filename = $this->pdfService->generateFilename('formulir-seminar', $seminar);

        return $pdf->download($filename);
    }

    /**
     * Download Formulir Pendaftaran Sidang
     */
    public function formulirSidang(SkripsiDefense $defense)
    {
        // Authorization check
        $this->authorize('view', $defense->application);

        $pdf = $this->pdfService->generateFormulirPendaftaranSidang($defense);
        $filename = $this->pdfService->generateFilename('formulir-sidang', $defense);

        return $pdf->download($filename);
    }

    /**
     * Download Transkrip Nilai Skripsi
     */
    public function transkripNilai(Application $application)
    {
        // Authorization check
        $this->authorize('view', $application);

        $pdf = $this->pdfService->generateTranskripNilai($application);
        $filename = $this->pdfService->generateFilename('transkrip-nilai', $application);

        return $pdf->download($filename);
    }

    /**
     * Download Surat Keterangan Lulus
     */
    public function suratKeteranganLulus(Application $application)
    {
        // Authorization check
        $this->authorize('view', $application);

        // Check if student has passed
        $defenseResult = ApplicationResultDefense::where('application_id', $application->id)
            ->whereIn('result', ['passed', 'passed_with_revision'])
            ->first();

        if (!$defenseResult) {
            abort(403, 'Mahasiswa belum lulus sidang skripsi');
        }

        $pdf = $this->pdfService->generateSuratKeteranganLulus($application);
        $filename = $this->pdfService->generateFilename('surat-keterangan-lulus', $application);

        return $pdf->download($filename);
    }

    /**
     * Preview PDF in browser (for testing)
     */
    public function preview(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        switch ($type) {
            case 'surat-tugas':
                $assignment = ApplicationAssignment::findOrFail($id);
                $pdf = $this->pdfService->generateSuratTugas($assignment);
                break;
            
            case 'ba-seminar':
                $seminar = SkripsiSeminar::find($id) ?? MbkmSeminar::findOrFail($id);
                $result = ApplicationResultSeminar::where('application_id', $seminar->application_id)->first();
                $pdf = $this->pdfService->generateBeritaAcaraSeminar($seminar, $result);
                break;
            
            case 'ba-sidang':
                $defense = SkripsiDefense::findOrFail($id);
                $result = ApplicationResultDefense::where('application_id', $defense->application_id)->first();
                $pdf = $this->pdfService->generateBeritaAcaraSidang($defense, $result);
                break;
            
            case 'transkrip-nilai':
                $application = Application::findOrFail($id);
                $pdf = $this->pdfService->generateTranskripNilai($application);
                break;
            
            case 'surat-keterangan-lulus':
                $application = Application::findOrFail($id);
                $pdf = $this->pdfService->generateSuratKeteranganLulus($application);
                break;
            
            default:
                abort(400, 'Invalid PDF type');
        }

        return $pdf->stream(); // Stream to browser instead of download
    }
}
