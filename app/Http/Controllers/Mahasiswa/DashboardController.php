<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\Announcement;
use App\Models\ApplicationSchedule;
use App\Models\ApplicationAction;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationScore;
use App\Models\Mahasiswa;
use App\Models\SkripsiRegistration;
use App\Models\SkripsiSeminar;
use App\Models\SkripsiDefense;
use App\Models\MbkmRegistration;
use App\Models\MbkmSeminar;
use App\Services\FormAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Aplikasi defense yang sudah difinalisasi admin (siap unduh rekap nilai).
     */
    private function getGraduationDocumentsContext(int $mahasiswaId): array
    {
        $defenseApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
            ->orderByDesc('created_at')
            ->first();

        if (!$defenseApp) {
            return [
                'graduationApplicationId' => null,
                'graduationFinalScore' => null,
                'graduationFinalGradeLetter' => null,
            ];
        }

        $finalized = ApplicationAction::where('application_id', $defenseApp->id)
            ->where('action_type', 'defense_finalized')
            ->exists();

        if (!$finalized) {
            return [
                'graduationApplicationId' => null,
                'graduationFinalScore' => null,
                'graduationFinalGradeLetter' => null,
            ];
        }

        $resultDefense = ApplicationResultDefense::where('application_id', $defenseApp->id)
            ->whereIn('result', ['passed', 'revision'])
            ->first();

        if (!$resultDefense) {
            return [
                'graduationApplicationId' => null,
                'graduationFinalScore' => null,
                'graduationFinalGradeLetter' => null,
            ];
        }

        return [
            'graduationApplicationId' => $defenseApp->id,
            'graduationFinalScore' => $resultDefense->final_score,
            'graduationFinalGradeLetter' => $resultDefense->final_grade_letter,
        ];
    }

    /**
     * Determine current phase of student
     * Phase 0: Belum pendaftaran
     * Phase 1: Sudah melakukan pendaftaran
     * Phase 2: Sudah melakukan seminar
     * Phase 3: Sudah melakukan sidang
     * Phase 4: Nilai sudah didapatkan
     */
    private function determinePhase($mahasiswa)
    {
        // Get active application
        $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['submitted', 'approved', 'scheduled', 'done', 'revision'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$activeApplication) {
            return [
                'phase' => 0,
                'phase_name' => 'Belum Mendaftar',
                'phase_description' => 'Anda belum memiliki aplikasi skripsi',
                'next_step' => 'Pilih jalur skripsi dan lakukan pendaftaran'
            ];
        }

        // Phase 4: Nilai sidang tersedia + finalisasi admin
        $resultDefense = ApplicationResultDefense::where('application_id', $activeApplication->id)->first();
        if ($resultDefense) {
            $hasCompletedScore = ApplicationScore::where('application_result_defence_id', $resultDefense->id)
                ->whereNotNull('score')
                ->exists();

            $finalized = ApplicationAction::where('application_id', $activeApplication->id)
                ->where('action_type', 'defense_finalized')
                ->exists();

            if ($hasCompletedScore && $finalized) {
                return [
                    'phase' => 4,
                    'phase_name' => 'Selesai (Finalisasi Admin)',
                    'phase_description' => 'Kelulusan Anda sudah difinalisasi. Rekap nilai tersedia.',
                    'next_step' => 'Unduh Rekap Nilai pada menu dokumen.',
                    'application' => $activeApplication
                ];
            }
        }

        // Phase 3: Check if defense is done
        if ($activeApplication->type == 'skripsi') {
            $defense = SkripsiDefense::where('application_id', $activeApplication->id)->first();
            if ($defense) {
                return [
                    'phase' => 3,
                    'phase_name' => 'Sudah Sidang',
                    'phase_description' => 'Anda sudah melakukan sidang skripsi',
                    'next_step' => 'Tunggu hasil penilaian dari dosen penguji',
                    'application' => $activeApplication
                ];
            }
        } elseif ($activeApplication->type == 'mbkm') {
            // MBKM juga punya defense di SkripsiDefense table
            $defense = SkripsiDefense::where('application_id', $activeApplication->id)->first();
            if ($defense) {
                return [
                    'phase' => 3,
                    'phase_name' => 'Sudah Sidang',
                    'phase_description' => 'Anda sudah melakukan sidang MBKM',
                    'next_step' => 'Tunggu hasil penilaian dari dosen penguji',
                    'application' => $activeApplication
                ];
            }
        }

        // Phase 2: Check if seminar is done
        if ($activeApplication->type == 'skripsi') {
            $seminar = SkripsiSeminar::where('application_id', $activeApplication->id)->first();
            if ($seminar) {
                return [
                    'phase' => 2,
                    'phase_name' => 'Sudah Seminar',
                    'phase_description' => 'Anda sudah melakukan seminar proposal',
                    'next_step' => 'Lakukan perbaikan dan daftar sidang skripsi',
                    'application' => $activeApplication
                ];
            }
        } elseif ($activeApplication->type == 'mbkm') {
            $seminar = MbkmSeminar::where('application_id', $activeApplication->id)->first();
            if ($seminar) {
                return [
                    'phase' => 2,
                    'phase_name' => 'Sudah Seminar',
                    'phase_description' => 'Anda sudah melakukan Review Kelayakan Proposal',
                    'next_step' => 'Lakukan perbaikan dan daftar sidang',
                    'application' => $activeApplication
                ];
            }
        }

        // Phase 1: Registration exists
        if ($activeApplication->type == 'skripsi') {
            $registration = SkripsiRegistration::where('application_id', $activeApplication->id)->first();
            if ($registration) {
                $supervisorAccepted = ApplicationAssignment::where('application_id', $activeApplication->id)
                    ->where('role', 'supervisor')
                    ->where('status', 'accepted')
                    ->exists();

                return [
                    'phase' => 1,
                    'phase_name' => 'Sudah Pendaftaran',
                    'phase_description' => 'Anda sudah melakukan pendaftaran skripsi',
                    'next_step' => $supervisorAccepted
                        ? 'Daftar seminar proposal'
                        : 'Tunggu persetujuan dosen pembimbing',
                    'application' => $activeApplication,
                    'supervisor_accepted' => $supervisorAccepted,
                ];
            }
        } elseif ($activeApplication->type == 'mbkm') {
            $registration = MbkmRegistration::where('application_id', $activeApplication->id)->first();
            if ($registration) {
                $supervisorAccepted = ApplicationAssignment::where('application_id', $activeApplication->id)
                    ->where('role', 'supervisor')
                    ->where('status', 'accepted')
                    ->exists();

                return [
                    'phase' => 1,
                    'phase_name' => 'Sudah Pendaftaran',
                    'phase_description' => 'Anda sudah melakukan pendaftaran MBKM',
                    'next_step' => $supervisorAccepted
                        ? 'Daftar Review Kelayakan Proposal'
                        : 'Tunggu persetujuan dosen pembimbing',
                    'application' => $activeApplication,
                    'supervisor_accepted' => $supervisorAccepted,
                ];
            }
        }

        // Default: Application exists but no registration yet
        return [
            'phase' => 0,
            'phase_name' => 'Aplikasi Dibuat',
            'phase_description' => 'Aplikasi sudah dibuat, belum melakukan pendaftaran',
            'next_step' => 'Lengkapi pendaftaran dan upload dokumen persyaratan',
            'application' => $activeApplication
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create')
                ->with('error', 'Silakan buat profil mahasiswa terlebih dahulu');
        }

        // Determine current phase
        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];
        $phaseName = $phaseData['phase_name'];
        $phaseDescription = $phaseData['phase_description'];
        $nextStep = $phaseData['next_step'];
        $supervisorAccepted = $phaseData['supervisor_accepted'] ?? false;
        $activeApplication = $phaseData['application'] ?? null;

        if ($activeApplication) {
            $activeApplication->load([
                'assignments.lecturer',
                'skripsiRegistration',
                'mbkmRegistration',
                'skripsiSeminar',
                'skripsiDefense',
                'mbkmSeminar',
            ]);
        }

        // Statistics
        $totalApplications = Application::where('mahasiswa_id', $mahasiswa->id)->count();
        $activeApplications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision'])
            ->count();
        $completedApplications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'done')
            ->count();

        // Get assignments (dosen)
        $assignments = [];
        $schedules = [];
        
        if ($activeApplication) {
            $assignments = ApplicationAssignment::where('application_id', $activeApplication->id)
                ->with('lecturer')
                ->get();
            
            $schedules = ApplicationSchedule::where('application_id', $activeApplication->id)
                ->with('ruang')
                ->orderBy('waktu', 'desc')
                ->limit(3)
                ->get();
            
            // Load revision_notes from corresponding registration table
            if ($activeApplication->status == 'revision') {
                if ($activeApplication->type == 'mbkm') {
                    $mbkmReg = MbkmRegistration::where('application_id', $activeApplication->id)->first();
                    if ($mbkmReg) {
                        $activeApplication->revision_notes = $mbkmReg->revision_notes;
                    }
                } elseif ($activeApplication->type == 'skripsi') {
                    $skripsiReg = SkripsiRegistration::where('application_id', $activeApplication->id)->first();
                    if ($skripsiReg) {
                        $activeApplication->revision_notes = $skripsiReg->revision_notes;
                    }
                }
            }
        }

        // Recent applications
        $recentApplications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        $graduationDocs = $this->getGraduationDocumentsContext($mahasiswa->id);

        $recentAnnouncements = Announcement::recentForAudience('mahasiswa');

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'totalApplications',
            'activeApplications',
            'completedApplications',
            'activeApplication',
            'assignments',
            'schedules',
            'recentApplications',
            'currentPhase',
            'phaseName',
            'phaseDescription',
            'nextStep',
            'supervisorAccepted',
            'allowedForms',
            'recentAnnouncements'
        ) + $graduationDocs);
    }

    public function aplikasi()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create');
        }

        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];

        $applications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->with([
                'assignments.lecturer',
                'skripsiRegistration',
                'mbkmRegistration',
                'skripsiSeminar',
                'skripsiDefense',
                'mbkmSeminar',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Load revision_notes for each application if status is revision
        foreach ($applications as $app) {
            if ($app->status == 'revision') {
                if ($app->type == 'mbkm') {
                    $mbkmReg = MbkmRegistration::where('application_id', $app->id)->first();
                    if ($mbkmReg) {
                        $app->revision_notes = $mbkmReg->revision_notes;
                    }
                } elseif ($app->type == 'skripsi') {
                    $skripsiReg = SkripsiRegistration::where('application_id', $app->id)->first();
                    if ($skripsiReg) {
                        $app->revision_notes = $skripsiReg->revision_notes;
                    }
                }
            }
        }

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        return view('mahasiswa.aplikasi', compact('mahasiswa', 'applications', 'currentPhase', 'allowedForms'));
    }

    public function bimbingan()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create');
        }

        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];

        // Get all applications with supervisor assignments
        $bimbinganData = Application::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mahasiswa'])
            ->get()
            ->map(function ($application) {
                $supervisors = ApplicationAssignment::where('application_id', $application->id)
                    ->where('role', 'supervisor')
                    ->with('lecturer')
                    ->get();
                
                $application->supervisors = $supervisors;
                return $application;
            });

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        return view('mahasiswa.bimbingan', compact('mahasiswa', 'bimbinganData', 'currentPhase', 'allowedForms'));
    }

    public function jadwal()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create');
        }

        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];

        // Get all schedules from all applications
        $applicationIds = Application::where('mahasiswa_id', $mahasiswa->id)->pluck('id');
        
        $schedules = ApplicationSchedule::whereIn('application_id', $applicationIds)
            ->with(['application.resultDefense', 'ruang'])
            ->orderBy('waktu', 'desc')
            ->get();

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        $scheduleAccess = $formAccessService->canAccessApplicationSchedule($mahasiswa->id);

        return view('mahasiswa.jadwal', compact('mahasiswa', 'schedules', 'currentPhase', 'allowedForms', 'scheduleAccess'));
    }

    public function dokumen()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create');
        }

        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];

        // Get all applications with documents
        $applications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mahasiswa'])
            ->orderBy('created_at', 'desc')
            ->get();

        $finalizedDefenseApplicationIds = ApplicationAction::where('action_type', 'defense_finalized')
            ->whereIn('application_id', $applications->pluck('id'))
            ->pluck('application_id')
            ->all();

        $graduationDocs = $this->getGraduationDocumentsContext($mahasiswa->id);

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        return view('mahasiswa.dokumen', compact(
            'mahasiswa',
            'applications',
            'currentPhase',
            'allowedForms',
            'finalizedDefenseApplicationIds'
        ) + $graduationDocs);
    }

    public function profile()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::with(['prodi', 'jenjang', 'fakultas'])
            ->find($user->mahasiswa_id);

        if (!$mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.create');
        }

        $phaseData = $this->determinePhase($mahasiswa);
        $currentPhase = $phaseData['phase'];

        // Get form access permissions
        $formAccessService = new FormAccessService();
        $allowedForms = $formAccessService->getAllowedForms($mahasiswa->id);

        return view('mahasiswa.profile', compact('mahasiswa', 'currentPhase', 'allowedForms'));
    }
}
