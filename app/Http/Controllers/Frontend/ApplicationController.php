<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of all applications for current user
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $applications = Application::where('mahasiswa_id', $mahasiswa->id)
            ->with(['skripsiRegistration', 'mbkmRegistration'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('frontend.applications.index', compact('applications', 'mahasiswa'));
    }
    
    /**
     * Display the specified application - redirects to appropriate show page
     */
    public function show($id)
    {
        $application = Application::findOrFail($id);
        
        // Verify ownership
        $user = Auth::user();
        if ($application->mahasiswa_id != $user->mahasiswa_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Redirect to appropriate controller based on type
        if ($application->type == 'skripsi') {
            return redirect()->route('frontend.skripsi.show', $application->id);
        } else {
            return redirect()->route('frontend.mbkm.show', $application->id);
        }
    }
}
