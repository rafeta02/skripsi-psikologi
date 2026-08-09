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
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();
            $app->instance('routes', $routes);

            $url = new \App\Routing\UrlGenerator(
                $routes,
                $app->rebinding('request', function ($app, $request) {
                    $app['url']->setRequest($request);
                }),
                $app['config']['app.asset_url']
            );

            $url->setSessionResolver(function () use ($app) {
                return $app['session'] ?? null;
            });

            $url->setKeyResolver(function () use ($app) {
                return $app->make('config')->get('app.key');
            });

            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });

            return $url;
        });
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

        View::composer(['dosen.*', 'partials.dosen.*'], function ($view) {
            if (array_key_exists('portalNav', $view->getData())) {
                return;
            }

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
