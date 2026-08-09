<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\SkripsiRegistration;
use App\Models\MbkmRegistration;
use App\Models\SkripsiSeminar;
use App\Models\MbkmSeminar;
use App\Models\SkripsiDefense;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\AdminAlert;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        // Thesis System Statistics
        $stats = [
            'total_applications' => Application::count(),
            'pending_verifications' => Application::where('status', 'submitted')->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'ongoing_defenses' => Application::where('stage', 'defense')->whereIn('status', ['submitted', 'approved', 'scheduled'])->count(),
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'pending_assignments' => ApplicationAssignment::where('status', 'assigned')->count(),
        ];

        // Applications by Type
        $applicationsByType = [
            'skripsi' => Application::where('type', 'skripsi')->count(),
            'mbkm' => Application::where('type', 'mbkm')->count(),
        ];

        // Applications by Stage
        $applicationsByStage = [
            'registration' => Application::where('stage', 'registration')->count(),
            'seminar' => Application::where('stage', 'seminar')->count(),
            'defense' => Application::where('stage', 'defense')->count(),
        ];

        // Recent Applications
        $recentApplications = Application::with(['mahasiswa.user', 'skripsiRegistration', 'mbkmRegistration'])
            ->latest()
            ->take(10)
            ->get();

        // Pending Verifications
        $pendingRegistrations = SkripsiRegistration::whereHas('application', function($q) {
            $q->where('status', 'submitted');
        })->count() + MbkmRegistration::whereHas('application', function($q) {
            $q->where('status', 'submitted');
        })->count();

        $pendingSeminars = SkripsiSeminar::whereNull('admin_validated_at')
            ->whereHas('application', function ($q) {
                $q->where('status', 'submitted');
            })->count()
            + MbkmSeminar::whereHas('application', function ($q) {
                $q->where('status', 'submitted');
            })->count();

        $adminAlerts = AdminAlert::unresolved()
            ->with(['dosen', 'application.mahasiswa'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $unresolvedAlertCount = AdminAlert::unresolved()->count();

        $pendingDefenses = SkripsiDefense::whereHas('application', function($q) {
            $q->where('status', 'submitted');
        })->count();

        return view('home', compact(
            'stats',
            'applicationsByType',
            'applicationsByStage',
            'recentApplications',
            'pendingRegistrations',
            'pendingSeminars',
            'pendingDefenses',
            'adminAlerts',
            'unresolvedAlertCount'
        ));
    }

    public function monitoring()
    {
        $applications = Application::with([
            'mahasiswa.user',
            'mahasiswa.prodi',
            'skripsiRegistration',
            'mbkmRegistration',
            'assignments.dosen'
        ])
        ->latest()
        ->paginate(20);

        return view('admin.monitoring', compact('applications'));
    }
}
