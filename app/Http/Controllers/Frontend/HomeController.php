<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the frontend landing page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // If user is authenticated, redirect to appropriate dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            switch ($user->level) {
                case 'MAHASISWA':
                    return redirect()->route('mahasiswa.dashboard');
                case 'DOSEN':
                    return redirect()->route('dosen.dashboard');
                case 'STAFF':
                    return redirect()->route('admin.home');
                default:
                    if ($user->is_admin) {
                        return redirect()->route('admin.home');
                    }
                    break;
            }
        }
        
        // Show public landing page for unauthenticated users
        return view('welcome');
    }
}
