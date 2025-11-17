<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\SkripsiRegistration;
use App\Models\MbkmRegistration;
use App\Models\SkripsiSeminar;
use App\Models\SkripsiDefense;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SkripsiDashboardController extends Controller
{
    public function index()
    {
        // Statistics - Include both skripsi and mbkm
        $stats = [
            'total_registrations' => SkripsiRegistration::count() + MbkmRegistration::count(),
            'pending_approvals' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('status', 'submitted')
                ->count(),
            'approved' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('status', 'approved')
                ->count(),
            'rejected' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('status', 'rejected')
                ->count(),
        ];

        // Students per stage - Include both types
        $stageStats = [
            'registration' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('stage', 'registration')
                ->count(),
            'seminar' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('stage', 'seminar')
                ->count(),
            'defense' => Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('stage', 'defense')
                ->count(),
        ];

        // Monthly submissions (last 6 months) - Include both types
        $monthlyData = Application::whereIn('type', ['skripsi', 'mbkm'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Approval rate - Include both types
        $totalSubmissions = Application::whereIn('type', ['skripsi', 'mbkm'])->count();
        $approvedCount = Application::whereIn('type', ['skripsi', 'mbkm'])->where('status', 'approved')->count();
        $approvalRate = $totalSubmissions > 0 ? round(($approvedCount / $totalSubmissions) * 100, 1) : 0;

        return view('admin.skripsi.dashboard', compact(
            'stats',
            'stageStats',
            'monthlyData',
            'approvalRate'
        ));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            // Get both Skripsi and MBKM registrations
            $skripsiQuery = SkripsiRegistration::with([
                'application',
                'application.mahasiswa',
                'theme',
                'preference_supervision',
                'assigned_supervisor'
            ])->get()->map(function ($item) {
                $item->registration_type = 'skripsi';
                return $item;
            });

            $mbkmQuery = MbkmRegistration::with([
                'application',
                'application.mahasiswa',
                'theme',
                'research_group',
                'preference_supervision'
            ])->get()->map(function ($item) {
                $item->registration_type = 'mbkm';
                $item->assigned_supervisor = null; // MBKM doesn't have assigned_supervisor field
                return $item;
            });

            // Merge both collections and sort by created_at
            $mergedData = $skripsiQuery->merge($mbkmQuery)->sortByDesc('created_at');

            return DataTables::of($mergedData)
                ->addColumn('student', function ($row) {
                    $mahasiswa = $row->application->mahasiswa ?? null;
                    if (!$mahasiswa) return 'N/A';
                    
                    $typeLabel = $row->registration_type === 'mbkm' 
                        ? '<span class="badge badge-primary badge-sm ml-1">MBKM</span>' 
                        : '<span class="badge badge-secondary badge-sm ml-1">Reguler</span>';
                    
                    return '<div><strong>' . $mahasiswa->nama . '</strong> ' . $typeLabel . '<br><small>' . $mahasiswa->nim . '</small></div>';
                })
                ->addColumn('title', function ($row) {
                    $title = $row->title ?? 'No Title';
                    
                    // Show MBKM title if exists
                    if ($row->registration_type === 'mbkm' && isset($row->title_mbkm)) {
                        $title = $row->title_mbkm . ' → ' . $title;
                    }
                    
                    return '<div class="text-truncate" style="max-width: 300px;" title="' . $title . '">' . $title . '</div>';
                })
                ->addColumn('theme', function ($row) {
                    return $row->theme->name ?? ($row->research_group->name ?? 'N/A');
                })
                ->addColumn('status', function ($row) {
                    $status = $row->application->status ?? 'unknown';
                    $badges = [
                        'submitted' => '<span class="badge badge-info">Submitted</span>',
                        'approved' => '<span class="badge badge-success">Approved</span>',
                        'rejected' => '<span class="badge badge-danger">Rejected</span>',
                        'revision' => '<span class="badge badge-warning">Revision Requested</span>',
                    ];
                    return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
                })
                ->addColumn('supervisor', function ($row) {
                    if ($row->assigned_supervisor) {
                        return '<span class="badge badge-success">' . $row->assigned_supervisor->nama . '</span>';
                    }
                    if ($row->preference_supervision) {
                        return '<small class="text-muted">Preferred: ' . $row->preference_supervision->nama . '</small>';
                    }
                    return '<span class="text-muted">Not assigned</span>';
                })
                ->addColumn('submitted_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y H:i') : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    $actions = '<div class="btn-group">';
                    
                    // Different routes based on type
                    if ($row->registration_type === 'mbkm') {
                        $actions .= '<a href="' . route('admin.mbkm-registrations.show', $row->id) . '" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                     </a>';
                    } else {
                        $actions .= '<a href="' . route('admin.skripsi-registrations.show', $row->id) . '" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                     </a>';
                    }
                    
                    if ($row->application && $row->application->status === 'submitted') {
                        $actions .= '<button class="btn btn-sm btn-success approve-btn" data-id="' . $row->id . '" data-type="' . $row->registration_type . '" title="Approve">
                                        <i class="fas fa-check"></i>
                                     </button>';
                        $actions .= '<button class="btn btn-sm btn-danger reject-btn" data-id="' . $row->id . '" data-type="' . $row->registration_type . '" title="Reject">
                                        <i class="fas fa-times"></i>
                                     </button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['student', 'title', 'status', 'supervisor', 'actions'])
                ->make(true);
        }
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'monthly');

        if ($type === 'monthly') {
            $data = Application::whereIn('type', ['skripsi', 'mbkm'])
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%b %Y") as label'),
                    DB::raw('count(*) as value')
                )
                ->groupBy('label')
                ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                ->get();

            return response()->json($data);
        }

        if ($type === 'status') {
            $data = Application::whereIn('type', ['skripsi', 'mbkm'])
                ->select('status', DB::raw('count(*) as value'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => ucfirst(str_replace('_', ' ', $item->status)),
                        'value' => $item->value
                    ];
                });

            return response()->json($data);
        }

        return response()->json([]);
    }
}

