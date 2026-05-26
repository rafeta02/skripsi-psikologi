<?php

namespace App\Services;

use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Application;
use App\Models\ApplicationSchedule;
use App\Models\SkripsiSeminar;
use App\Models\MbkmSeminar;
use App\Models\SkripsiDefense;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationResultDefense;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfGeneratorService
{
    /**
     * Generate Surat Tugas (Assignment Letter) PDF
     * For Pembimbing, Reviewer, or Examiner
     */
    public function generateSuratTugas(ApplicationAssignment $assignment)
    {
        $data = [
            'documentNumber' => $this->generateDocumentNumber('ST', $assignment),
            'date' => $this->formatDate(now()),
            'assignment' => $assignment,
            'mahasiswa' => $assignment->application->mahasiswa,
            'dosen' => $assignment->lecturer,
            'application' => $assignment->application,
            'roleText' => $this->getRoleText($assignment->role),
            'prodi' => $assignment->application->mahasiswa->prodi,
        ];

        $pdf = Pdf::loadView('pdf.admin.surat-tugas', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Berita Acara Seminar (Seminar Minutes)
     */
    public function generateBeritaAcaraSeminar($seminar, ApplicationResultSeminar $result = null)
    {
        // Get pembimbing and reviewers from assignments
        $assignments = $seminar->application->assignments;
        $pembimbing = $assignments->where('role', 'supervisor')->first();
        $reviewers = $assignments->where('role', 'reviewer')->take(2);

        // Get schedule if exists
        $schedule = ApplicationSchedule::where('application_id', $seminar->application_id)
            ->where('schedule_type', 'skripsi_seminar')
            ->first();

        $data = [
            'documentNumber' => $this->generateDocumentNumber('BAS', $seminar),
            'date' => $this->formatDate(now()),
            'seminar' => $seminar,
            'result' => $result,
            'mahasiswa' => $seminar->application->mahasiswa,
            'application' => $seminar->application,
            'pembimbing' => $pembimbing?->lecturer,
            'reviewers' => $reviewers->pluck('lecturer'),
            'schedule' => $schedule,
            'prodi' => $seminar->application->mahasiswa->prodi,
        ];

        $pdf = Pdf::loadView('pdf.reports.berita-acara-seminar', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Berita Acara Sidang (Defense Minutes)
     */
    public function generateBeritaAcaraSidang(SkripsiDefense $defense, ApplicationResultDefense $result = null)
    {
        // Get pembimbing and examiners from assignments
        $assignments = $defense->application->assignments;
        $pembimbing = $assignments->where('role', 'supervisor')->first();
        $examiners = $assignments->where('role', 'examiner')->take(2);

        // Get schedule if exists
        $schedule = ApplicationSchedule::where('application_id', $defense->application_id)
            ->where('schedule_type', 'skripsi_defense')
            ->first();

        // Get final score
        $finalScore = ApplicationScore::where('application_id', $defense->application_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $data = [
            'documentNumber' => $this->generateDocumentNumber('BAD', $defense),
            'date' => $this->formatDate(now()),
            'defense' => $defense,
            'result' => $result,
            'mahasiswa' => $defense->application->mahasiswa,
            'application' => $defense->application,
            'pembimbing' => $pembimbing?->lecturer,
            'examiners' => $examiners->pluck('lecturer'),
            'schedule' => $schedule,
            'finalScore' => $finalScore,
            'prodi' => $defense->application->mahasiswa->prodi,
        ];

        $pdf = Pdf::loadView('pdf.reports.berita-acara-sidang', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Lembar Penilaian (Assessment Form)
     */
    public function generateLembarPenilaian(ApplicationScore $score)
    {
        $data = [
            'documentNumber' => $this->generateDocumentNumber('LP', $score),
            'date' => $this->formatDate($score->created_at),
            'score' => $score,
            'mahasiswa' => $score->application->mahasiswa,
            'application' => $score->application,
            'examiner' => $score->examiner,
            'prodi' => $score->application->mahasiswa->prodi,
            'assessmentType' => $this->getAssessmentType($score),
        ];

        $pdf = Pdf::loadView('pdf.dosen.lembar-penilaian', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Kartu Bimbingan (Supervision Card)
     */
    public function generateKartuBimbingan(Application $application)
    {
        // Get all supervision logs (if you have a model for this)
        // For now, we'll use assignments as placeholder
        $pembimbingAssignment = $application->assignments()
            ->where('role', 'supervisor')
            ->first();

        $data = [
            'documentNumber' => $this->generateDocumentNumber('KB', $application),
            'mahasiswa' => $application->mahasiswa,
            'application' => $application,
            'pembimbing' => $pembimbingAssignment?->lecturer,
            'prodi' => $application->mahasiswa->prodi,
            'sessions' => [], // You can populate this from a guidance_logs table
        ];

        $pdf = Pdf::loadView('pdf.mahasiswa.kartu-bimbingan', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Formulir Pendaftaran Seminar
     */
    public function generateFormulirPendaftaranSeminar($seminar)
    {
        $data = [
            'documentNumber' => $this->generateDocumentNumber('FPS', $seminar),
            'date' => $this->formatDate($seminar->created_at),
            'seminar' => $seminar,
            'mahasiswa' => $seminar->application->mahasiswa,
            'application' => $seminar->application,
            'pembimbing' => $seminar->application->assignments()
                ->where('role', 'supervisor')
                ->first()?->lecturer,
            'prodi' => $seminar->application->mahasiswa->prodi,
        ];

        $pdf = Pdf::loadView('pdf.mahasiswa.formulir-pendaftaran-seminar', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Formulir Pendaftaran Sidang
     */
    public function generateFormulirPendaftaranSidang(SkripsiDefense $defense)
    {
        $data = [
            'documentNumber' => $this->generateDocumentNumber('FPD', $defense),
            'date' => $this->formatDate($defense->created_at),
            'defense' => $defense,
            'mahasiswa' => $defense->application->mahasiswa,
            'application' => $defense->application,
            'pembimbing' => $defense->application->assignments()
                ->where('role', 'supervisor')
                ->first()?->lecturer,
            'prodi' => $defense->application->mahasiswa->prodi,
        ];

        $pdf = Pdf::loadView('pdf.mahasiswa.formulir-pendaftaran-sidang', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Document Number
     * Format: [TYPE]/[YEAR]/[PRODI]/[SEQUENCE]
     */
    private function generateDocumentNumber(string $type, $model): string
    {
        $year = now()->year;
        $prodi = 'PSI'; // Default, can be dynamic from model
        
        // Get sequence number based on type and year
        $sequence = $this->getSequenceNumber($type, $year);
        
        return sprintf('%s/%d/%s/%03d', $type, $year, $prodi, $sequence);
    }

    /**
     * Get sequence number for document type
     */
    private function getSequenceNumber(string $type, int $year): int
    {
        // This should be stored in database for persistence
        // For now, return a placeholder
        // TODO: Implement proper sequence tracking in database
        return rand(1, 999);
    }

    /**
     * Format date to Indonesian format
     */
    private function formatDate($date): string
    {
        if (!$date) return '-';
        
        Carbon::setLocale('id');
        return Carbon::parse($date)->isoFormat('D MMMM YYYY');
    }

    /**
     * Get role text in Indonesian
     */
    private function getRoleText(string $role): string
    {
        return match($role) {
            'supervisor' => 'Pembimbing',
            'reviewer' => 'Reviewer',
            'examiner' => 'Penguji',
            default => ucfirst($role),
        };
    }

    /**
     * Get assessment type text
     */
    private function getAssessmentType(ApplicationScore $score): string
    {
        // Determine based on application stage
        $stage = $score->application->stage ?? 'unknown';
        
        return match($stage) {
            'proposal_review' => 'Penilaian Proposal',
            'seminar' => 'Penilaian Seminar',
            'defense' => 'Penilaian Sidang',
            default => 'Penilaian',
        };
    }

    /**
     * Add watermark to PDF
     */
    public function addWatermark(Pdf $pdf, string $text = 'DRAFT'): Pdf
    {
        // Watermark implementation would go here
        // DomPDF doesn't support watermarks natively
        // Would need to add to the view template
        return $pdf;
    }

    /**
     * Generate Transkrip Nilai Skripsi (Thesis Grade Transcript)
     */
    public function generateTranskripNilai(Application $application)
    {
        $defenseResult = ApplicationResultDefense::where('application_id', $application->id)
            ->with(['scores.examiner'])
            ->first();

        $scores = $defenseResult ? $defenseResult->scores->filter(fn ($s) => $s->score !== null) : collect();

        $averageScore = $defenseResult ? (float) $defenseResult->final_score : 0;
        $finalGrade = $defenseResult ? (string) ($defenseResult->final_grade_letter ?? $this->calculateGradeLetter($averageScore)) : $this->calculateGradeLetter($averageScore);

        $pembimbing = $this->resolveSupervisorForMahasiswa($application);

        // Get defense info
        $defense = SkripsiDefense::where('application_id', $application->id)->first();

        $data = [
            'documentNumber' => $this->generateDocumentNumber('TN', $application),
            'date' => $this->formatDate(now()),
            'application' => $application,
            'mahasiswa' => $application->mahasiswa,
            'prodi' => $application->mahasiswa->prodi,
            'pembimbing' => $pembimbing,
            'defense' => $defense,
            'scores' => $scores,
            'averageScore' => round($averageScore, 2),
            'finalGrade' => $finalGrade,
            'graduationDate' => $defense ? $this->formatDate($defense->created_at) : $this->formatDate(now()),
        ];

        $pdf = Pdf::loadView('pdf.mahasiswa.transkrip-nilai', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate Surat Keterangan Lulus (Certificate of Completion)
     */
    public function generateSuratKeteranganLulus(Application $application)
    {
        // Get defense result
        $defenseResult = ApplicationResultDefense::where('application_id', $application->id)
            ->first();

        // Get defense info
        $defense = SkripsiDefense::where('application_id', $application->id)->first();

        $data = [
            'documentNumber' => $this->generateDocumentNumber('SKL', $application),
            'date' => $this->formatDate(now()),
            'application' => $application,
            'mahasiswa' => $application->mahasiswa,
            'prodi' => $application->mahasiswa->prodi,
            'defense' => $defense,
            'defenseResult' => $defenseResult,
            'graduationDate' => $defense ? $this->formatDate($defense->created_at) : $this->formatDate(now()),
        ];

        $pdf = Pdf::loadView('pdf.mahasiswa.surat-keterangan-lulus', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    private function resolveSupervisorForMahasiswa(Application $application)
    {
        $regApp = Application::where('mahasiswa_id', $application->mahasiswa_id)
            ->where('stage', 'registration')
            ->orderByDesc('created_at')
            ->first();

        $targetApp = $regApp ?? $application;

        return $targetApp->assignments()
            ->where('role', 'supervisor')
            ->where('status', 'accepted')
            ->first()?->lecturer;
    }

    /**
     * Calculate grade letter from score
     */
    private function calculateGradeLetter(float $score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'A-';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'B-';
        if ($score >= 60) return 'C+';
        if ($score >= 55) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }

    /**
     * Generate filename for download
     */
    public function generateFilename(string $type, $model): string
    {
        $timestamp = now()->format('Y-m-d');
        
        return match($type) {
            'surat-tugas' => "Surat_Tugas_{$model->lecturer->nama}_{$timestamp}.pdf",
            'ba-seminar' => "BA_Seminar_{$model->application->mahasiswa->nim}_{$timestamp}.pdf",
            'ba-sidang' => "BA_Sidang_{$model->application->mahasiswa->nim}_{$timestamp}.pdf",
            'lembar-penilaian' => "Lembar_Penilaian_{$model->application->mahasiswa->nim}_{$timestamp}.pdf",
            'kartu-bimbingan' => "Kartu_Bimbingan_{$model->mahasiswa->nim}_{$timestamp}.pdf",
            'formulir-seminar' => "Formulir_Seminar_{$model->application->mahasiswa->nim}_{$timestamp}.pdf",
            'formulir-sidang' => "Formulir_Sidang_{$model->application->mahasiswa->nim}_{$timestamp}.pdf",
            'transkrip-nilai' => "Transkrip_Nilai_Skripsi_{$model->mahasiswa->nim}_{$timestamp}.pdf",
            'surat-keterangan-lulus' => "Surat_Keterangan_Lulus_{$model->mahasiswa->nim}_{$timestamp}.pdf",
            default => "Document_{$timestamp}.pdf",
        };
    }
}
