<?php

namespace App\Providers;

use App\Services\DosenPortalService;
use App\Services\FormAccessService;
use App\Services\MahasiswaPortalService;
use App\Models\Application;
use App\Observers\ApplicationObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Application::observe(ApplicationObserver::class);

        if (!function_exists('highlight_keywords')) {
            function highlight_keywords(?string $text, string $query): string
            {
                $escaped = e($text ?? '');
                $keywords = preg_split('/\s+/', mb_strtolower(trim($query)));

                foreach ($keywords as $keyword) {
                    if (mb_strlen($keyword) < 2) {
                        continue;
                    }
                    $escaped = preg_replace(
                        '/(' . preg_quote($keyword, '/') . ')/iu',
                        '<mark class="bg-warning px-1">$1</mark>',
                        $escaped
                    );
                }

                return $escaped;
            }
        }

        \App\Models\ApplicationScore::observe(\App\Observers\ApplicationScoreObserver::class);

        View::composer([
            'layouts.mahasiswa',
            'partials.mahasiswa.*',
            'mahasiswa.*',
        ], function ($view) {
            $mahasiswaId = Auth::user()?->mahasiswa_id;
            $allowedForms = [];
            $portalNav = [];
            $quickActions = [];
            $processTimeline = [];

            try {
                $allowedForms = $mahasiswaId
                    ? (new FormAccessService())->getAllowedForms($mahasiswaId)
                    : [];

                $portal = new MahasiswaPortalService();
                $portalNav = $portal->getNavigation($allowedForms);
                $quickActions = $portal->getQuickActions($allowedForms);
                $processTimeline = $mahasiswaId ? $portal->getProcessTimeline($mahasiswaId) : [];
            } catch (\Throwable $e) {
                report($e);
            }

            $view->with([
                'allowedForms' => $allowedForms,
                'portalNav' => $portalNav,
                'quickActions' => $quickActions,
                'processTimeline' => $processTimeline,
            ]);
        });

        View::composer('layouts.dosen', function ($view) {
            $portal = new DosenPortalService();
            $dosenId = $portal->resolveDosenId();

            $view->with([
                'portalNav' => $portal->getNavigation(),
                'quickActions' => $dosenId ? $portal->getQuickActions($dosenId) : [],
                'activityTimeline' => $dosenId ? $portal->getActivityTimeline($dosenId) : [],
                'portalStats' => $dosenId ? $portal->getSummaryStats($dosenId) : [],
            ]);
        });
    }
}
