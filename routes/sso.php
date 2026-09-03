<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoController;

Route::prefix('sso')
->name('sso.')
->group(function () {
   Route::get('login', [SsoController::class, 'login'])->name('login');
   Route::get('logout', [SsoController::class, 'logout'])->name('logout');
   Route::get('sls', [SsoController::class, 'sls'])->name('sls');
   Route::get('metadata', [SsoController::class, 'metadata'])->name('metadata');
   Route::post('acs', [SsoController::class, 'acs'])->name('acs');
});
