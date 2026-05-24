<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathSelectionController extends Controller
{
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Check if user is mahasiswa
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized access');
        }
        
        // Get mahasiswa data
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silahkan hubungi administrator.');
        }
        
        // Check if mahasiswa already has an active application
        $activeApplication = Application::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();
        
        if ($activeApplication) {
            // Redirect to appropriate page based on application type and stage
            if ($activeApplication->type == 'skripsi') {
                return redirect()->route('frontend.skripsi.show', $activeApplication->id)
                    ->with('info', 'Anda sudah memiliki aplikasi Skripsi Reguler yang aktif.');
            } else {
                return redirect()->route('frontend.mbkm.show', $activeApplication->id)
                    ->with('info', 'Anda sudah memiliki aplikasi MBKM yang aktif.');
            }
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
        
        // Check if mahasiswa already has an active application
        if (Application::hasActiveApplication($mahasiswa->id)) {
            return redirect()->back()->with('error', 'Anda sudah memiliki aplikasi yang aktif.');
        }
        
        // Create new application
        $application = Application::create([
            'mahasiswa_id' => $mahasiswa->id,
            'type' => $request->path_type,
            'stage' => 'registration',
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        
        // Redirect to appropriate registration form
        if ($request->path_type == 'skripsi') {
            return redirect()->route('frontend.skripsi.create', $application->id)
                ->with('success', 'Silahkan lengkapi form pendaftaran Skripsi Reguler.');
        } else {
            return redirect()->route('frontend.mbkm.create', $application->id)
                ->with('success', 'Silahkan lengkapi form pendaftaran MBKM.');
        }
    }
}
