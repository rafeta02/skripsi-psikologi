<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\Keilmuan;
use App\Models\Dosen;
use App\Models\ResearchGroup;
use App\Models\MbkmGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MbkmRegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        // Get all MBKM applications for this mahasiswa
        $applications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('type', 'mbkm')
            ->with('mbkmRegistration')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('frontend.mbkm.index', compact('applications', 'mahasiswa'));
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
        if ($application->mbkmRegistration) {
            return redirect()->route('frontend.mbkm.edit', $application->id)
                ->with('info', 'Form pendaftaran sudah ada. Silahkan edit jika diperlukan.');
        }
        
        // Get data for dropdowns
        $keilmuans = Keilmuan::pluck('name', 'id');
        $dosens = Dosen::pluck('nama', 'id');
        $researchGroups = ResearchGroup::pluck('name', 'id');
        
        return view('frontend.mbkm.create', compact('application', 'keilmuans', 'dosens', 'researchGroups'));
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
            'research_group_id' => 'required|exists:research_groups,id',
            'preference_supervision_id' => 'required|exists:dosens,id',
            'theme_id' => 'required|exists:keilmuans,id',
            'title_mbkm' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'total_sks_taken' => 'required|integer|min:0',
            'nilai_mk_kuantitatif' => 'required|string|max:10',
            'nilai_mk_kualitatif' => 'required|string|max:10',
            'nilai_mk_statistika_dasar' => 'required|string|max:10',
            'nilai_mk_statistika_lanjutan' => 'required|string|max:10',
            'nilai_mk_konstruksi_tes' => 'required|string|max:10',
            'nilai_mk_tps' => 'required|string|max:10',
            'sks_mkp_taken' => 'required|integer|min:0',
            'note' => 'nullable|string',
            'khs_all' => 'required|array',
            'khs_all.*' => 'required|file|mimes:pdf|max:5120',
            'krs_latest' => 'required|file|mimes:pdf|max:5120',
            'spp' => 'required|file|mimes:pdf|max:5120',
            'proposal_mbkm' => 'required|file|mimes:pdf|max:10240',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
            // Group members (optional)
            'group_members' => 'nullable|array',
            'group_members.*.mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'group_members.*.role' => 'nullable|in:ketua,anggota',
        ]);
        
        try {
            DB::beginTransaction();
            
            $validated['application_id'] = $application->id;
            $validated['created_by_id'] = $user->id;
            
            $registration = MbkmRegistration::create($validated);
            
            // Handle file uploads
            if ($request->hasFile('khs_all')) {
                foreach ($request->file('khs_all') as $file) {
                    $registration->addMedia($file)->toMediaCollection('khs_all');
                }
            }
            
            if ($request->hasFile('krs_latest')) {
                $registration->addMedia($request->file('krs_latest'))->toMediaCollection('krs_latest');
            }
            
            if ($request->hasFile('spp')) {
                $registration->addMedia($request->file('spp'))->toMediaCollection('spp');
            }
            
            if ($request->hasFile('proposal_mbkm')) {
                $registration->addMedia($request->file('proposal_mbkm'))->toMediaCollection('proposal_mbkm');
            }
            
            if ($request->hasFile('recognition_form')) {
                $registration->addMedia($request->file('recognition_form'))->toMediaCollection('recognition_form');
            }
            
            // Handle group members
            if ($request->has('group_members')) {
                foreach ($request->group_members as $member) {
                    if (isset($member['mahasiswa_id']) && $member['mahasiswa_id']) {
                        MbkmGroupMember::create([
                            'mbkm_registration_id' => $registration->id,
                            'mahasiswa_id' => $member['mahasiswa_id'],
                            'role' => $member['role'] ?? 'anggota',
                        ]);
                    }
                }
            }
            
            // Update application status
            $application->update(['status' => 'submitted']);
            
            DB::commit();
            
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', 'Pendaftaran MBKM berhasil disimpan. Menunggu verifikasi admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function show($applicationId)
    {
        $application = Application::with(['mbkmRegistration.groupMembers', 'mahasiswa'])->findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('frontend.mbkm.show', compact('application'));
    }
    
    public function edit($applicationId)
    {
        $application = Application::with('mbkmRegistration')->findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if can edit
        if (!in_array($application->status, ['submitted', 'rejected'])) {
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('error', 'Pendaftaran tidak dapat diedit pada status ini.');
        }
        
        $keilmuans = Keilmuan::pluck('name', 'id');
        $dosens = Dosen::pluck('nama', 'id');
        $researchGroups = ResearchGroup::pluck('name', 'id');
        $registration = $application->mbkmRegistration;
        
        return view('frontend.mbkm.edit', compact('application', 'registration', 'keilmuans', 'dosens', 'researchGroups'));
    }
    
    public function update(Request $request, $applicationId)
    {
        $application = Application::with('mbkmRegistration')->findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        $validated = $request->validate([
            'research_group_id' => 'required|exists:research_groups,id',
            'preference_supervision_id' => 'required|exists:dosens,id',
            'theme_id' => 'required|exists:keilmuans,id',
            'title_mbkm' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'total_sks_taken' => 'required|integer|min:0',
            'nilai_mk_kuantitatif' => 'required|string|max:10',
            'nilai_mk_kualitatif' => 'required|string|max:10',
            'nilai_mk_statistika_dasar' => 'required|string|max:10',
            'nilai_mk_statistika_lanjutan' => 'required|string|max:10',
            'nilai_mk_konstruksi_tes' => 'required|string|max:10',
            'nilai_mk_tps' => 'required|string|max:10',
            'sks_mkp_taken' => 'required|integer|min:0',
            'note' => 'nullable|string',
            'khs_all' => 'nullable|array',
            'khs_all.*' => 'nullable|file|mimes:pdf|max:5120',
            'krs_latest' => 'nullable|file|mimes:pdf|max:5120',
            'spp' => 'nullable|file|mimes:pdf|max:5120',
            'proposal_mbkm' => 'nullable|file|mimes:pdf|max:10240',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $registration = $application->mbkmRegistration;
            $registration->update($validated);
            
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
            
            if ($request->hasFile('spp')) {
                $registration->clearMediaCollection('spp');
                $registration->addMedia($request->file('spp'))->toMediaCollection('spp');
            }
            
            if ($request->hasFile('proposal_mbkm')) {
                $registration->clearMediaCollection('proposal_mbkm');
                $registration->addMedia($request->file('proposal_mbkm'))->toMediaCollection('proposal_mbkm');
            }
            
            if ($request->hasFile('recognition_form')) {
                $registration->clearMediaCollection('recognition_form');
                $registration->addMedia($request->file('recognition_form'))->toMediaCollection('recognition_form');
            }
            
            // Update application status back to submitted
            $application->update(['status' => 'submitted']);
            
            DB::commit();
            
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', 'Pendaftaran MBKM berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
