<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\SkripsiRegistration;
use App\Models\Keilmuan;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkripsiRegistrationController extends Controller
{
    /**
     * Route skripsi-registrations/{id} memakai ID pendaftaran;
     * route skripsi/{application} memakai ID aplikasi.
     */
    protected function resolveApplication(int|string $id): Application
    {
        $numericId = (int) $id;
        $routeName = request()->route()?->getName() ?? '';

        if (str_contains($routeName, 'skripsi-registrations')) {
            $registration = SkripsiRegistration::find($numericId);
            if ($registration) {
                return Application::findOrFail($registration->application_id);
            }
        }

        $application = Application::find($numericId);
        if ($application) {
            return $application;
        }

        $registration = SkripsiRegistration::findOrFail($numericId);

        return Application::findOrFail($registration->application_id);
    }

    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        // Get all skripsi applications for this mahasiswa
        $applications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('type', 'skripsi')
            ->with([
                'skripsiRegistration.themes',
                'skripsiRegistration.preference_supervision',
                'assignments',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('frontend.skripsi.index', compact('applications', 'mahasiswa'));
    }
    
    public function create($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if registration already exists
        if ($application->skripsiRegistration) {
            return redirect()->route('frontend.skripsi.edit', $application->id)
                ->with('info', 'Form pendaftaran sudah ada. Silahkan edit jika diperlukan.');
        }
        
        // Get data for dropdowns
        $keilmuans = Keilmuan::pluck('name', 'id');
        $dosens = Dosen::pluck('nama', 'id');
        
        return view('frontend.skripsi.create', compact('application', 'keilmuans', 'dosens'));
    }
    
    public function store(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        $validated = $request->validate([
            'theme_ids' => 'required|array|min:1',
            'theme_ids.*' => 'required|exists:keilmuans,id',
            'title' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'abstract' => 'required|string',
            'tps_lecturer_id' => 'nullable|exists:dosens,id',
            'preference_supervision_id' => 'nullable|exists:dosens,id',
            'khs_all' => 'required|array',
            'khs_all.*' => 'required|file|mimes:pdf|max:5120',
            'krs_latest' => 'required|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $themeIds = $validated['theme_ids'];
            unset($validated['theme_ids']);

            $validated['application_id'] = $application->id;
            $validated['created_by_id'] = $user->id;
            $validated['theme_id'] = $themeIds[0];

            $registration = SkripsiRegistration::create($validated);
            $registration->themes()->sync($themeIds);
            
            // Get mahasiswa data for filename
            $mahasiswa = $application->mahasiswa;
            $nim = $mahasiswa->nim ?? 'unknown';
            $name = \Str::slug($mahasiswa->nama ?? 'mahasiswa');
            $timestamp = now()->format('Ymd_His');
            
            // Handle KHS files upload with custom naming
            if ($request->hasFile('khs_all')) {
                $index = 1;
                foreach ($request->file('khs_all') as $file) {
                    // Format: SKRIPSI_KHS_NIM_NAMA_SEMESTER-X_YYYYMMDD_HHMMSS.pdf
                    $customFileName = sprintf(
                        'SKRIPSI_KHS_%s_%s_SEMESTER-%d_%s.pdf',
                        $nim,
                        strtoupper($name),
                        $index,
                        $timestamp
                    );
                    
                    $registration->addMedia($file)
                        ->usingFileName($customFileName)
                        ->toMediaCollection('khs_all');
                    
                    $index++;
                }
            }
            
            // Handle KRS file upload with custom naming
            if ($request->hasFile('krs_latest')) {
                // Format: SKRIPSI_KRS_NIM_NAMA_YYYYMMDD_HHMMSS.pdf
                $customFileName = sprintf(
                    'SKRIPSI_KRS_%s_%s_%s.pdf',
                    $nim,
                    strtoupper($name),
                    $timestamp
                );
                
                $registration->addMedia($request->file('krs_latest'))
                    ->usingFileName($customFileName)
                    ->toMediaCollection('krs_latest');
            }
            
            // Update application status and submitted_at timestamp
            $application->update([
                'status' => 'submitted',
                'submitted_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('frontend.skripsi.show', $application->id)
                ->with('success', 'Pendaftaran skripsi berhasil disimpan. Menunggu verifikasi admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function show($applicationId)
    {
        $application = $this->resolveApplication($applicationId);
        $application->load(['skripsiRegistration.themes', 'skripsiRegistration.preference_supervision', 'skripsiRegistration.tps_lecturer', 'mahasiswa', 'assignments.lecturer']);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('frontend.skripsi.show', compact('application'));
    }
    
    public function edit($applicationId)
    {
        $application = $this->resolveApplication($applicationId);
        $application->load(['skripsiRegistration.themes']);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if can edit
        if (!in_array($application->status, ['submitted', 'rejected', 'revision'])) {
            return redirect()->route('frontend.skripsi-registrations.show', $application->skripsiRegistration?->id ?? $application->id)
                ->with('error', 'Pendaftaran tidak dapat diedit pada status ini.');
        }
        
        $keilmuans = Keilmuan::pluck('name', 'id');
        $dosens = Dosen::pluck('nama', 'id');
        $registration = $application->skripsiRegistration;

        if (!$registration) {
            return redirect()->route('frontend.skripsi.create', $application->id)
                ->with('info', 'Lengkapi form pendaftaran terlebih dahulu.');
        }

        return view('frontend.skripsi.edit', compact('application', 'registration', 'keilmuans', 'dosens'));
    }
    
    public function update(Request $request, $applicationId)
    {
        $application = $this->resolveApplication($applicationId);
        $application->load(['skripsiRegistration.themes']);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($application->status, ['submitted', 'rejected', 'revision'])) {
            return redirect()->route('frontend.skripsi-registrations.show', $application->skripsiRegistration?->id ?? $application->id)
                ->with('error', 'Pendaftaran tidak dapat diperbarui pada status ini.');
        }
        
        $validated = $request->validate([
            'theme_ids' => 'required|array|min:1',
            'theme_ids.*' => 'required|exists:keilmuans,id',
            'title' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'abstract' => 'required|string',
            'tps_lecturer_id' => 'nullable|exists:dosens,id',
            'preference_supervision_id' => 'nullable|exists:dosens,id',
            'khs_all' => 'nullable|array',
            'khs_all.*' => 'nullable|file|mimes:pdf|max:5120',
            'krs_latest' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $themeIds = $validated['theme_ids'];
            unset($validated['theme_ids']);
            $validated['theme_id'] = $themeIds[0];

            $registration = $application->skripsiRegistration;
            $wasRevision = $application->status === 'revision';
            $registration->update($validated);
            $registration->themes()->sync($themeIds);
            
            // Handle file uploads
            if ($request->hasFile('khs_all')) {
                $registration->clearMediaCollection('khs_all');
                foreach ($request->file('khs_all') as $file) {
                    $registration->addMedia($file)->toMediaCollection('khs_all');
                }
            }
            
            if ($request->hasFile('krs_latest')) {
                $registration->clearMediaCollection('krs_latest');
                $registration->addMedia($request->file('krs_latest'))->toMediaCollection('krs_latest');
            }
            
            $application->update(['status' => 'submitted']);
            if ($wasRevision) {
                $registration->update(['revision_notes' => null]);
            }
            
            DB::commit();
            
            return redirect()->route('frontend.skripsi-registrations.show', $registration->id)
                ->with('success', $wasRevision
                    ? 'Revisi pendaftaran berhasil dikirim ulang. Menunggu verifikasi admin.'
                    : 'Pendaftaran skripsi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
