<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Mahasiswa;
use App\Services\MbkmGroupProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathSelectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized access');
        }
        
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silahkan hubungi administrator.');
        }

        $groupService = app(MbkmGroupProgressService::class);

        // Anggota kelompok: arahkan ke form syarat individu
        if ($groupService->isFollowerAnggota($mahasiswa->id)) {
            return redirect()->route('frontend.mbkm.member-requirements')
                ->with('info', 'Anda tergabung sebagai anggota kelompok MBKM. Lengkapi syarat individu Anda.');
        }
        
        $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('is_group_mirror', false)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();
        
        if ($activeApplication) {
            if ($activeApplication->type == 'skripsi') {
                return redirect()->route('frontend.skripsi.show', $activeApplication->id)
                    ->with('info', 'Anda sudah memiliki aplikasi Skripsi Reguler yang aktif.');
            }

            return redirect()->route('frontend.mbkm.show', $activeApplication->id)
                ->with('info', 'Anda sudah memiliki aplikasi MBKM yang aktif.');
        }

        // Mirror aktif tanpa membership terdeteksi
        $mirror = Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('is_group_mirror', true)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderByDesc('id')
            ->first();

        if ($mirror?->parent_application_id) {
            return redirect()->route('frontend.mbkm.show', $mirror->parent_application_id)
                ->with('info', 'Anda tergabung sebagai anggota kelompok MBKM.');
        }
        
        return view('frontend.choose-path', compact('mahasiswa'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'path_type' => 'required|in:skripsi,mbkm'
        ]);
        
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $groupService = app(MbkmGroupProgressService::class);
        if ($groupService->isFollowerAnggota($mahasiswa->id)) {
            return redirect()->back()->with('error', 'Anda sudah tergabung di kelompok MBKM dan tidak dapat membuat aplikasi baru.');
        }
        
        if (Application::where('mahasiswa_id', $mahasiswa->id)
            ->where('is_group_mirror', false)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki aplikasi yang aktif.');
        }
        
        $application = Application::create([
            'mahasiswa_id' => $mahasiswa->id,
            'type' => $request->path_type,
            'stage' => 'registration',
            'status' => 'submitted',
            'submitted_at' => now(),
            'is_group_mirror' => false,
        ]);
        
        if ($request->path_type == 'skripsi') {
            return redirect()->route('frontend.skripsi.create', $application->id)
                ->with('success', 'Silahkan lengkapi form pendaftaran Skripsi Reguler.');
        }

        return redirect()->route('frontend.mbkm.create', $application->id)
            ->with('success', 'Silahkan lengkapi form pendaftaran MBKM.');
    }
}
