<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\Keilmuan;
use App\Models\Dosen;
use App\Models\ResearchGroup;
use App\Models\MbkmGroupMember;
use App\Models\Mahasiswa;
use App\Services\MbkmGroupProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $keilmuans = Keilmuan::orderBy('name')->pluck('name', 'id');
        $researchGroups = ResearchGroup::orderBy('name')->pluck('name', 'id');
        $dosensByGroup = Dosen::query()
            ->whereNotNull('riset_grup_id')
            ->orderBy('nama')
            ->get(['id', 'nama', 'riset_grup_id'])
            ->groupBy('riset_grup_id')
            ->map(fn ($items) => $items->map(fn ($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
            ])->values())
            ->toArray();

        return view('frontend.mbkm.create', compact(
            'application',
            'keilmuans',
            'researchGroups',
            'dosensByGroup'
        ));
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
            'preference_supervision_id' => [
                'required',
                'exists:dosens,id',
                function ($attribute, $value, $fail) use ($request) {
                    $belongs = Dosen::where('id', $value)
                        ->where('riset_grup_id', $request->input('research_group_id'))
                        ->exists();
                    if (!$belongs) {
                        $fail('Dosen pembimbing harus berasal dari research group yang dipilih.');
                    }
                },
            ],
            'theme_ids' => 'required|array|min:1',
            'theme_ids.*' => 'required|exists:keilmuans,id',
            'title_mbkm' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
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
            
            $themeIds = $validated['theme_ids'];
            unset($validated['theme_ids']);

            $validated['application_id'] = $application->id;
            $validated['created_by_id'] = $user->id;
            // Simpan tema pertama di theme_id untuk kompatibilitas data lama
            $validated['theme_id'] = $themeIds[0];
            unset($validated['group_members']);
            
            $registration = MbkmRegistration::create($validated);
            $registration->themes()->sync($themeIds);
            
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
            
            // Handle group members + mirror progress
            $groupService = app(MbkmGroupProgressService::class);
            $groupService->syncGroupMembers(
                $registration,
                $request->input('group_members', []),
                (int) $user->mahasiswa_id
            );
            
            // Update application status
            $application->update(['status' => 'submitted']);
            
            DB::commit();
            
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', 'Pendaftaran MBKM berhasil disimpan. Menunggu verifikasi admin.');
                
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cari mahasiswa by NIM untuk form anggota kelompok.
     */
    public function searchMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|min:3',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return response()->json(['found' => false, 'message' => 'Mahasiswa tidak ditemukan.']);
        }

        if ((int) $mahasiswa->id === (int) $user->mahasiswa_id) {
            return response()->json(['found' => false, 'message' => 'Tidak dapat menambahkan diri sendiri.']);
        }

        try {
            app(MbkmGroupProgressService::class)->assertMemberEligible((int) $mahasiswa->id);
        } catch (ValidationException $e) {
            $msg = collect($e->errors())->flatten()->first() ?? 'Mahasiswa tidak eligible.';
            return response()->json(['found' => false, 'message' => $msg]);
        }

        return response()->json([
            'found' => true,
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ],
        ]);
    }
    
    public function show($applicationId)
    {
        $application = Application::with([
            'mbkmRegistration.groupMembers.mahasiswa',
            'mbkmRegistration.themes',
            'mahasiswa',
        ])->findOrFail($applicationId);

        // Mirror → tampilkan milik ketua
        if ($application->is_group_mirror && $application->parent_application_id) {
            return redirect()->route('frontend.mbkm.show', $application->parent_application_id);
        }
        
        $user = Auth::user();
        $groupService = app(MbkmGroupProgressService::class);

        if (!$groupService->canViewOwnerApplication((int) $user->mahasiswa_id, $application)) {
            abort(403, 'Unauthorized access');
        }

        $isGroupFollower = $groupService->isFollowerAnggota((int) $user->mahasiswa_id);
        
        return view('frontend.mbkm.show', compact('application', 'isGroupFollower'));
    }
    
    public function edit($applicationId)
    {
        $application = Application::with([
            'mbkmRegistration.themes',
            'mbkmRegistration.groupMembers.mahasiswa',
        ])->findOrFail($applicationId);
        
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
        
        $keilmuans = Keilmuan::orderBy('name')->pluck('name', 'id');
        $researchGroups = ResearchGroup::orderBy('name')->pluck('name', 'id');
        $dosensByGroup = Dosen::query()
            ->whereNotNull('riset_grup_id')
            ->orderBy('nama')
            ->get(['id', 'nama', 'riset_grup_id'])
            ->groupBy('riset_grup_id')
            ->map(fn ($items) => $items->map(fn ($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
            ])->values())
            ->toArray();
        $registration = $application->mbkmRegistration;
        
        return view('frontend.mbkm.edit', compact(
            'application',
            'registration',
            'keilmuans',
            'researchGroups',
            'dosensByGroup'
        ));
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
            'preference_supervision_id' => [
                'required',
                'exists:dosens,id',
                function ($attribute, $value, $fail) use ($request) {
                    $belongs = Dosen::where('id', $value)
                        ->where('riset_grup_id', $request->input('research_group_id'))
                        ->exists();
                    if (!$belongs) {
                        $fail('Dosen pembimbing harus berasal dari research group yang dipilih.');
                    }
                },
            ],
            'theme_ids' => 'required|array|min:1',
            'theme_ids.*' => 'required|exists:keilmuans,id',
            'title_mbkm' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'total_sks_taken' => 'required|integer|min:0',
            'nilai_mk_kuantitatif' => 'required|string|max:10',
            'nilai_mk_kualitatif' => 'required|string|max:10',
            'nilai_mk_statistika_dasar' => 'required|string|max:10',
            'nilai_mk_statistika_lanjutan' => 'required|string|max:10',
            'nilai_mk_konstruksi_tes' => 'required|string|max:10',
            'nilai_mk_tps' => 'required|string|max:10',
            'sks_mkp_taken' => 'required|integer|min:0',
            'note' => 'nullable|string',
            'group_members' => 'nullable|array',
            'group_members.*.mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'group_members.*.role' => 'nullable|in:ketua,anggota',
            'khs_all' => 'nullable|array',
            'khs_all.*' => 'nullable|file|mimes:pdf|max:5120',
            'krs_latest' => 'nullable|file|mimes:pdf|max:5120',
            'spp' => 'nullable|file|mimes:pdf|max:5120',
            'proposal_mbkm' => 'nullable|file|mimes:pdf|max:10240',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $themeIds = $validated['theme_ids'];
            unset($validated['theme_ids']);
            $groupMembersInput = $request->input('group_members', []);
            unset($validated['group_members']);
            $validated['theme_id'] = $themeIds[0];

            $registration = $application->mbkmRegistration;
            $registration->update($validated);
            $registration->themes()->sync($themeIds);

            app(MbkmGroupProgressService::class)->syncGroupMembers(
                $registration,
                $groupMembersInput,
                (int) $user->mahasiswa_id
            );
            
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
