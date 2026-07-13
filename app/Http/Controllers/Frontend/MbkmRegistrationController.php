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
        
        // Check if registration already exists (bypass tenant scope — akses via ownership aplikasi)
        $existingRegistration = MbkmRegistration::withoutGlobalScopes()
            ->where('application_id', $application->id)
            ->first();

        if ($existingRegistration) {
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
        
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }

        if (MbkmRegistration::withoutGlobalScopes()->where('application_id', $application->id)->exists()) {
            return redirect()->route('frontend.mbkm.edit', $application->id)
                ->with('info', 'Form pendaftaran sudah ada.');
        }
        
        $validated = $request->validate([
            // Kelompok
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
            'note' => 'nullable|string',
            'proposal_mbkm' => 'required|file|mimes:pdf|max:10240',
            'group_members' => 'nullable|array',
            'group_members.*.mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'group_members.*.role' => 'nullable|in:ketua,anggota',
            // Individu ketua
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
            'khs_all' => 'required|array',
            'khs_all.*' => 'required|file|mimes:pdf|max:5120',
            'krs_latest' => 'required|file|mimes:pdf|max:5120',
            'spp' => 'required|file|mimes:pdf|max:5120',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $themeIds = $validated['theme_ids'];
            $groupService = app(MbkmGroupProgressService::class);

            $registration = MbkmRegistration::create([
                'application_id' => $application->id,
                'created_by_id' => $user->id,
                'research_group_id' => $validated['research_group_id'],
                'preference_supervision_id' => $validated['preference_supervision_id'],
                'theme_id' => $themeIds[0],
                'title_mbkm' => $validated['title_mbkm'],
                'note' => $validated['note'] ?? null,
                'group_status' => 'draft',
                // Kompatibilitas tampilan lama: salin judul ketua ke registration
                'title' => $validated['title'],
                'title_en' => $validated['title_en'] ?? null,
                'total_sks_taken' => $validated['total_sks_taken'],
                'sks_mkp_taken' => $validated['sks_mkp_taken'],
                'nilai_mk_kuantitatif' => $validated['nilai_mk_kuantitatif'],
                'nilai_mk_kualitatif' => $validated['nilai_mk_kualitatif'],
                'nilai_mk_statistika_dasar' => $validated['nilai_mk_statistika_dasar'],
                'nilai_mk_statistika_lanjutan' => $validated['nilai_mk_statistika_lanjutan'],
                'nilai_mk_konstruksi_tes' => $validated['nilai_mk_konstruksi_tes'],
                'nilai_mk_tps' => $validated['nilai_mk_tps'],
            ]);

            $registration->themes()->sync($themeIds);

            if ($request->hasFile('proposal_mbkm')) {
                $registration->addMediaWithCustomName($request->file('proposal_mbkm'), 'proposal_mbkm');
            }

            $groupService->syncGroupMembers(
                $registration->fresh(['application']),
                $request->input('group_members', []),
                (int) $user->mahasiswa_id
            );

            $ketuaMember = MbkmGroupMember::where('mbkm_registration_id', $registration->id)
                ->where('mahasiswa_id', $user->mahasiswa_id)
                ->firstOrFail();

            $groupService->saveIndividualRequirements($ketuaMember, $validated, $request);

            $application->update(['status' => 'submitted']);
            
            DB::commit();
            
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', 'Draft kelompok tersimpan. Lengkapi syarat individu semua anggota, lalu submit pengajuan kelompok.');
                
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form syarat individu untuk anggota kelompok.
     */
    public function memberRequirements()
    {
        $user = Auth::user();
        $mahasiswaId = (int) $user->mahasiswa_id;
        $groupService = app(MbkmGroupProgressService::class);

        $member = $groupService->findMemberRecord($mahasiswaId);
        if (!$member) {
            return redirect()->route('frontend.choose-path')
                ->with('error', 'Anda belum tergabung dalam kelompok MBKM.');
        }

        $registration = MbkmRegistration::withoutGlobalScopes()
            ->with(['application.mahasiswa', 'themes', 'research_group', 'preference_supervision', 'groupMembers.mahasiswa'])
            ->findOrFail($member->mbkm_registration_id);

        $locked = $registration->isGroupSubmitted();

        return view('frontend.mbkm.member-requirements', compact('member', 'registration', 'locked'));
    }

    public function updateMemberRequirements(Request $request)
    {
        $user = Auth::user();
        $mahasiswaId = (int) $user->mahasiswa_id;
        $groupService = app(MbkmGroupProgressService::class);

        $member = $groupService->findMemberRecord($mahasiswaId);
        if (!$member) {
            abort(403, 'Unauthorized access');
        }

        $registration = MbkmRegistration::withoutGlobalScopes()->findOrFail($member->mbkm_registration_id);
        if ($registration->isGroupSubmitted()) {
            return redirect()->route('frontend.mbkm.member-requirements')
                ->with('error', 'Pengajuan kelompok sudah dikirim. Syarat individu tidak dapat diubah.');
        }

        $needsFiles = !$member->hasCompleteDocuments();

        $validated = $request->validate([
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
            'khs_all' => ($needsFiles ? 'required' : 'nullable') . '|array',
            'khs_all.*' => 'nullable|file|mimes:pdf|max:5120',
            'krs_latest' => ($needsFiles ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'spp' => ($needsFiles ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($needsFiles && (!$request->hasFile('khs_all') || !$request->hasFile('krs_latest') || !$request->hasFile('spp'))) {
            return redirect()->back()->withInput()->with('error', 'Dokumen KHS, KRS, dan SPP wajib diunggah.');
        }

        try {
            DB::beginTransaction();
            $groupService->saveIndividualRequirements($member, $validated, $request);
            DB::commit();

            return redirect()->route('frontend.mbkm.member-requirements')
                ->with('success', 'Syarat individu berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Ketua submit pengajuan kelompok ke admin setelah semua individu lengkap.
     */
    public function submitGroup($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $user = Auth::user();

        if ((int) $application->mahasiswa_id !== (int) $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }

        $registration = MbkmRegistration::withoutGlobalScopes()
            ->with('groupMembers')
            ->where('application_id', $application->id)
            ->firstOrFail();

        try {
            app(MbkmGroupProgressService::class)->submitGroup($registration);

            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', 'Pengajuan kelompok berhasil dikirim. Menunggu verifikasi admin.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
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
        $application = Application::with(['mahasiswa'])->findOrFail($applicationId);

        // Mirror → tampilkan milik ketua
        if ($application->is_group_mirror && $application->parent_application_id) {
            return redirect()->route('frontend.mbkm.show', $application->parent_application_id);
        }
        
        $user = Auth::user();
        $groupService = app(MbkmGroupProgressService::class);

        if (!$groupService->canViewOwnerApplication((int) $user->mahasiswa_id, $application)) {
            abort(403, 'Unauthorized access');
        }

        $registration = MbkmRegistration::withoutGlobalScopes()
            ->with(['groupMembers.mahasiswa', 'themes', 'preference_supervision', 'research_group'])
            ->where('application_id', $application->id)
            ->first();

        $application->setRelation('mbkmRegistration', $registration);

        $isGroupFollower = $groupService->isFollowerAnggota((int) $user->mahasiswa_id);
        $myMember = $registration
            ? $registration->groupMembers->firstWhere('mahasiswa_id', (int) $user->mahasiswa_id)
            : null;

        // Ketua tanpa row member (data lama): pastikan ada / fallback ke ketua di list
        if (!$myMember && $registration && (int) $application->mahasiswa_id === (int) $user->mahasiswa_id) {
            $myMember = $registration->groupMembers->firstWhere('role', 'ketua')
                ?? $registration->groupMembers->first();
        }

        if ($myMember) {
            $myMember->load('media');
        }
        $submitCheck = $registration
            ? $groupService->canSubmitGroup($registration)
            : ['allowed' => false, 'message' => null, 'summary' => ['total' => 0, 'complete' => 0, 'pending' => 0, 'ready' => false]];
        $isKetua = !$isGroupFollower && (int) $application->mahasiswa_id === (int) $user->mahasiswa_id;
        
        return view('frontend.mbkm.show', compact(
            'application',
            'isGroupFollower',
            'myMember',
            'submitCheck',
            'isKetua'
        ));
    }
    
    public function edit($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }

        // Support alias route that may pass registration id
        $registration = MbkmRegistration::withoutGlobalScopes()
            ->with(['themes', 'groupMembers.mahasiswa'])
            ->where('application_id', $application->id)
            ->first();

        if (!$registration) {
            $registration = MbkmRegistration::withoutGlobalScopes()
                ->with(['themes', 'groupMembers.mahasiswa', 'application'])
                ->find($applicationId);

            if ($registration && $registration->application) {
                $application = $registration->application;
                if ((int) $application->mahasiswa_id !== (int) $user->mahasiswa_id) {
                    abort(403, 'Unauthorized access');
                }
            }
        }

        if (!$registration) {
            return redirect()->route('frontend.mbkm.create', $application->id)
                ->with('info', 'Silakan lengkapi form pendaftaran terlebih dahulu.');
        }

        if ($registration->isGroupSubmitted() && $application->status !== 'revision') {
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('error', 'Pengajuan kelompok sudah dikirim dan tidak dapat diedit.');
        }

        // Revisi admin: pastikan form terbuka (group_status draft)
        if ($application->status === 'revision' && $registration->isGroupSubmitted()) {
            $registration->update(['group_status' => 'draft']);
            $registration->refresh();
        }
        
        // Check if can edit
        if (!in_array($application->status, ['submitted', 'rejected', 'revision'])) {
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

        $ketuaMember = $registration->groupMembers->firstWhere('mahasiswa_id', (int) $user->mahasiswa_id);
        
        return view('frontend.mbkm.edit', compact(
            'application',
            'registration',
            'ketuaMember',
            'keilmuans',
            'researchGroups',
            'dosensByGroup'
        ));
    }
    
    public function update(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }

        $registration = MbkmRegistration::withoutGlobalScopes()
            ->where('application_id', $application->id)
            ->first();

        if (!$registration) {
            return redirect()->route('frontend.mbkm.create', $application->id)
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        if ($registration->isGroupSubmitted() && $application->status !== 'revision') {
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('error', 'Pengajuan kelompok sudah dikirim. Hubungi admin untuk revisi.');
        }

        if ($application->status === 'revision' && $registration->isGroupSubmitted()) {
            $registration->update(['group_status' => 'draft']);
            $registration->refresh();
        }

        $ketuaMember = MbkmGroupMember::where('mbkm_registration_id', $registration->id)
            ->where('mahasiswa_id', $user->mahasiswa_id)
            ->first();

        $needsFiles = !$ketuaMember || !$ketuaMember->hasCompleteDocuments();
        
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
            'note' => 'nullable|string',
            'group_members' => 'nullable|array',
            'group_members.*.mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'group_members.*.role' => 'nullable|in:ketua,anggota',
            'proposal_mbkm' => 'nullable|file|mimes:pdf|max:10240',
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
            'khs_all' => ($needsFiles ? 'required' : 'nullable') . '|array',
            'khs_all.*' => 'nullable|file|mimes:pdf|max:5120',
            'krs_latest' => ($needsFiles ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'spp' => ($needsFiles ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            'recognition_form' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            $groupService = app(MbkmGroupProgressService::class);
            
            $themeIds = $validated['theme_ids'];

            $registration->update([
                'research_group_id' => $validated['research_group_id'],
                'preference_supervision_id' => $validated['preference_supervision_id'],
                'theme_id' => $themeIds[0],
                'title_mbkm' => $validated['title_mbkm'],
                'note' => $validated['note'] ?? null,
                'title' => $validated['title'],
                'title_en' => $validated['title_en'] ?? null,
                'total_sks_taken' => $validated['total_sks_taken'],
                'sks_mkp_taken' => $validated['sks_mkp_taken'],
                'nilai_mk_kuantitatif' => $validated['nilai_mk_kuantitatif'],
                'nilai_mk_kualitatif' => $validated['nilai_mk_kualitatif'],
                'nilai_mk_statistika_dasar' => $validated['nilai_mk_statistika_dasar'],
                'nilai_mk_statistika_lanjutan' => $validated['nilai_mk_statistika_lanjutan'],
                'nilai_mk_konstruksi_tes' => $validated['nilai_mk_konstruksi_tes'],
                'nilai_mk_tps' => $validated['nilai_mk_tps'],
            ]);
            $registration->themes()->sync($themeIds);

            if ($request->hasFile('proposal_mbkm')) {
                $registration->clearMediaCollection('proposal_mbkm');
                $registration->addMediaWithCustomName($request->file('proposal_mbkm'), 'proposal_mbkm');
            }

            $groupService->syncGroupMembers(
                $registration->fresh(['application']),
                $request->input('group_members', []),
                (int) $user->mahasiswa_id
            );

            $ketuaMember = MbkmGroupMember::where('mbkm_registration_id', $registration->id)
                ->where('mahasiswa_id', $user->mahasiswa_id)
                ->firstOrFail();

            $groupService->saveIndividualRequirements($ketuaMember, $validated, $request);

            $wasRevision = $application->status === 'revision';

            // Simpan draft: jangan anggap sudah diajukan ke admin.
            // Status revision tetap revision sampai ketua submit ulang kelompok.
            if (!$wasRevision && !in_array($application->status, ['approved', 'scheduled', 'result', 'done'], true)) {
                $application->update(['status' => 'submitted']);
            }
            
            DB::commit();
            
            return redirect()->route('frontend.mbkm.show', $application->id)
                ->with('success', $wasRevision
                    ? 'Revisi draft kelompok disimpan. Submit ulang pengajuan setelah perbaikan selesai.'
                    : 'Draft kelompok diperbarui. Submit pengajuan setelah semua anggota lengkap.');
                
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
