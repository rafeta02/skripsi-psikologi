<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Application;
use App\Models\SkripsiRegistration;
use App\Models\SkripsiSeminar;
use App\Models\SkripsiDefense;
use App\Models\MbkmRegistration;
use App\Models\MbkmSeminar;
use App\Models\ApplicationSchedule;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationAssignment;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $dosen = $user->dosen;
        
        // Check profile completion
        $profileComplete = true;
        $missingFields = [];
        $profileEditRoute = null;
        
        if ($user->level === 'MAHASISWA') {
            // Check if mahasiswa_id is null (profile not created yet)
            if (!$user->mahasiswa_id || !$mahasiswa) {
                $profileComplete = false;
                $missingFields = ['Profil Mahasiswa belum dibuat. Silakan buat profil Anda terlebih dahulu.'];
                $profileEditRoute = route('frontend.mahasiswa-profile.create');
            } else {
                // Check if all required fields are filled
                $check = $this->checkMahasiswaProfile($mahasiswa);
                $profileComplete = $check['complete'];
                $missingFields = $check['missing'];
                $profileEditRoute = route('frontend.mahasiswa-profile.edit');
            }
        } elseif ($user->level === 'DOSEN') {
            // Check if dosen_id is null (profile not created yet)
            if (!$user->dosen_id || !$dosen) {
                $profileComplete = false;
                $missingFields = ['Profil Dosen belum dibuat. Silakan buat profil Anda terlebih dahulu.'];
                $profileEditRoute = route('frontend.dosen-profile.create');
            } else {
                // Check if all required fields are filled
                $check = $this->checkDosenProfile($dosen);
                $profileComplete = $check['complete'];
                $missingFields = $check['missing'];
                $profileEditRoute = route('frontend.dosen-profile.edit');
            }
        }
        
        // Get all applications for this mahasiswa
        $applications = Application::where('mahasiswa_id', $mahasiswa->id ?? null)
            ->with([
                'mahasiswa',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get active application (latest submitted or in progress)
        $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id ?? null)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Get detailed information based on application type and stage
        $currentPhase = null;
        $nextSteps = [];
        $documents = [];
        $assignments = [];
        $schedules = [];
        
        if ($activeApplication) {
            // Get assignments (dosen pembimbing, reviewer, penguji)
            $assignments = ApplicationAssignment::where('application_id', $activeApplication->id)
                ->with('lecturer')
                ->get();
            
            // Get schedules
            $schedules = ApplicationSchedule::where('application_id', $activeApplication->id)
                ->with('ruang')
                ->orderBy('waktu', 'desc')
                ->get();
            
            // Determine current phase and next steps based on type and stage
            if ($activeApplication->type === 'skripsi') {
                $currentPhase = $this->getSkripsiPhase($activeApplication);
                $nextSteps = $this->getSkripsiNextSteps($activeApplication);
                $documents = $this->getSkripsiDocuments($activeApplication);
            } elseif ($activeApplication->type === 'mbkm') {
                $currentPhase = $this->getMbkmPhase($activeApplication);
                $nextSteps = $this->getMbkmNextSteps($activeApplication);
                $documents = $this->getMbkmDocuments($activeApplication);
            }
        }
        
        return view('frontend.home', compact(
            'mahasiswa',
            'dosen',
            'applications',
            'activeApplication',
            'currentPhase',
            'nextSteps',
            'documents',
            'assignments',
            'schedules',
            'profileComplete',
            'missingFields',
            'profileEditRoute'
        ));
    }
    
    /**
     * Check if mahasiswa profile is complete
     */
    private function checkMahasiswaProfile($mahasiswa)
    {
        $requiredFields = [
            'nim' => 'NIM',
            'nama' => 'Nama',
            'tahun_masuk' => 'Tahun Masuk',
            'kelas' => 'Kelas',
            'prodi_id' => 'Program Studi',
            'jenjang_id' => 'Jenjang',
            'fakultas_id' => 'Fakultas',
            'tanggal_lahir' => 'Tanggal Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'gender' => 'Jenis Kelamin',
            'alamat' => 'Alamat',
        ];
        
        $missing = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($mahasiswa->$field)) {
                $missing[] = $label;
            }
        }
        
        return [
            'complete' => empty($missing),
            'missing' => $missing
        ];
    }
    
    /**
     * Check if dosen profile is complete
     */
    private function checkDosenProfile($dosen)
    {
        $requiredFields = [
            'nip' => 'NIP',
            'nidn' => 'NIDN',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'prodi_id' => 'Program Studi',
            'jenjang_id' => 'Jenjang',
            'fakultas_id' => 'Fakultas',
            'riset_grup_id' => 'Riset Grup',
        ];
        
        $missing = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($dosen->$field)) {
                $missing[] = $label;
            }
        }
        
        return [
            'complete' => empty($missing),
            'missing' => $missing
        ];
    }
    
    private function getSkripsiPhase($application)
    {
        $phase = [
            'name' => 'Belum Dimulai',
            'description' => 'Aplikasi belum diproses',
            'progress' => 0
        ];
        
        switch ($application->stage) {
            case 'registration':
                $registration = SkripsiRegistration::where('application_id', $application->id)->first();
                if ($registration) {
                    $phase = [
                        'name' => 'Pendaftaran Topik Skripsi',
                        'description' => 'Menunggu verifikasi admin dan penugasan dosen pembimbing',
                        'progress' => 20
                    ];
                }
                break;
                
            case 'seminar':
                $seminar = SkripsiSeminar::where('application_id', $application->id)->first();
                if ($seminar) {
                    $phase = [
                        'name' => 'Seminar Proposal',
                        'description' => 'Tahap seminar proposal skripsi',
                        'progress' => 50
                    ];
                }
                break;
                
            case 'defense':
                $defense = SkripsiDefense::where('application_id', $application->id)->first();
                if ($defense) {
                    $phase = [
                        'name' => 'Sidang Skripsi',
                        'description' => 'Tahap sidang akhir skripsi',
                        'progress' => 80
                    ];
                }
                break;
        }
        
        if ($application->status === 'done') {
            $phase['progress'] = 100;
            $phase['name'] = 'Selesai';
            $phase['description'] = 'Proses skripsi telah selesai';
        }
        
        return $phase;
    }
    
    private function getMbkmPhase($application)
    {
        $phase = [
            'name' => 'Belum Dimulai',
            'description' => 'Aplikasi belum diproses',
            'progress' => 0
        ];
        
        switch ($application->stage) {
            case 'registration':
                $registration = MbkmRegistration::where('application_id', $application->id)->first();
                if ($registration) {
                    $phase = [
                        'name' => 'Pendaftaran MBKM',
                        'description' => 'Menunggu verifikasi admin dan persetujuan dosen pembimbing',
                        'progress' => 20
                    ];
                }
                break;
                
            case 'seminar':
                $seminar = MbkmSeminar::where('application_id', $application->id)->first();
                if ($seminar) {
                    $phase = [
                        'name' => 'Seminar MBKM',
                        'description' => 'Tahap seminar proposal MBKM',
                        'progress' => 50
                    ];
                }
                break;
                
            case 'defense':
                $defense = SkripsiDefense::where('application_id', $application->id)->first();
                if ($defense) {
                    $phase = [
                        'name' => 'Sidang Skripsi MBKM',
                        'description' => 'Tahap sidang akhir skripsi MBKM',
                        'progress' => 80
                    ];
                }
                break;
        }
        
        if ($application->status === 'done') {
            $phase['progress'] = 100;
            $phase['name'] = 'Selesai';
            $phase['description'] = 'Proses skripsi MBKM telah selesai';
        }
        
        return $phase;
    }
    
    private function getSkripsiNextSteps($application)
    {
        $steps = [];
        
        if ($application->status === 'submitted' && $application->stage === 'registration') {
            $steps[] = 'Menunggu admin memverifikasi dokumen pendaftaran';
            $steps[] = 'Admin akan menugaskan dosen pembimbing sesuai preferensi Anda';
        } elseif ($application->status === 'approved' && $application->stage === 'registration') {
            $steps[] = 'Menyusun proposal skripsi dengan bimbingan dosen';
            $steps[] = 'Mendaftar seminar proposal melalui form Skripsi Seminar';
        } elseif ($application->stage === 'seminar') {
            $steps[] = 'Menunggu admin menetapkan dosen reviewer';
            $steps[] = 'Mengatur jadwal seminar proposal';
            $steps[] = 'Melaksanakan seminar proposal';
            $steps[] = 'Upload hasil seminar';
        } elseif ($application->stage === 'defense') {
            $steps[] = 'Menunggu admin menetapkan dosen penguji';
            $steps[] = 'Mengatur jadwal sidang skripsi';
            $steps[] = 'Melaksanakan sidang skripsi';
            $steps[] = 'Upload hasil sidang dan dokumen lengkap';
        }
        
        return $steps;
    }
    
    private function getMbkmNextSteps($application)
    {
        $steps = [];
        
        if ($application->status === 'submitted' && $application->stage === 'registration') {
            $steps[] = 'Menunggu admin memverifikasi kelengkapan dokumen MBKM';
            $steps[] = 'Menunggu persetujuan dosen pembimbing yang dipilih';
        } elseif ($application->status === 'approved' && $application->stage === 'registration') {
            $steps[] = 'Menyusun proposal MBKM dengan kelompok';
            $steps[] = 'Mendaftar seminar MBKM melalui form MBKM Seminar';
        } elseif ($application->stage === 'seminar') {
            $steps[] = 'Menunggu admin menetapkan dosen reviewer';
            $steps[] = 'Mengatur jadwal seminar MBKM';
            $steps[] = 'Melaksanakan seminar MBKM';
            $steps[] = 'Upload hasil seminar';
        } elseif ($application->stage === 'defense') {
            $steps[] = 'Menunggu admin menetapkan dosen penguji';
            $steps[] = 'Mengatur jadwal sidang skripsi';
            $steps[] = 'Melaksanakan sidang skripsi';
            $steps[] = 'Upload hasil sidang dan dokumen lengkap';
        }
        
        return $steps;
    }
    
    private function getSkripsiDocuments($application)
    {
        $docs = [];
        
        $registration = SkripsiRegistration::where('application_id', $application->id)->first();
        if ($registration) {
            $docs['registration'] = $registration;
        }
        
        $seminar = SkripsiSeminar::where('application_id', $application->id)->first();
        if ($seminar) {
            $docs['seminar'] = $seminar;
        }
        
        $defense = SkripsiDefense::where('application_id', $application->id)->first();
        if ($defense) {
            $docs['defense'] = $defense;
        }
        
        return $docs;
    }
    
    private function getMbkmDocuments($application)
    {
        $docs = [];
        
        $registration = MbkmRegistration::where('application_id', $application->id)->first();
        if ($registration) {
            $docs['registration'] = $registration;
        }
        
        $seminar = MbkmSeminar::where('application_id', $application->id)->first();
        if ($seminar) {
            $docs['seminar'] = $seminar;
        }
        
        $defense = SkripsiDefense::where('application_id', $application->id)->first();
        if ($defense) {
            $docs['defense'] = $defense;
        }
        
        return $docs;
    }
}
