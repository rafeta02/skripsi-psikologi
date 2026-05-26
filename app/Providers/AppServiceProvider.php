<?php

namespace App\Providers;

use App\Services\FormAccessService;
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
        \App\Models\ApplicationScore::observe(\App\Observers\ApplicationScoreObserver::class);

        View::composer('layouts.mahasiswa', function ($view) {
            $mahasiswaId = Auth::user()?->mahasiswa_id;
            $allowedForms = $mahasiswaId
                ? (new FormAccessService())->getAllowedForms($mahasiswaId)
                : [];

            $view->with('allowedForms', $allowedForms);
        });
    }
}
