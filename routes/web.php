<?php

// Public landing page
Route::get('/', 'App\Http\Controllers\Frontend\HomeController@index')->name('home');

// SSO Routes
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/sso/login', 'SsoController@login')->name('sso.login');
    Route::post('/sso/acs', 'SsoController@acs')->name('sso.acs');
    Route::get('/sso/logout', 'SsoController@logout')->name('sso.logout');
    Route::get('/sso/sls', 'SsoController@sls')->name('sso.sls');
    Route::get('/sso/metadata', 'SsoController@metadata')->name('sso.metadata');
});

Auth::routes(['register' => false]);

// PDF Generation Routes (Accessible by all authenticated users with proper authorization)
Route::group(['prefix' => 'pdf', 'as' => 'pdf.', 'middleware' => ['auth']], function () {
    Route::get('surat-tugas/{assignment}', 'App\Http\Controllers\PdfController@suratTugas')->name('surat-tugas');
    Route::get('berita-acara-seminar/{seminar}', 'App\Http\Controllers\PdfController@beritaAcaraSeminar')->name('ba-seminar');
    Route::get('berita-acara-sidang/{defense}', 'App\Http\Controllers\PdfController@beritaAcaraSidang')->name('ba-sidang');
    Route::get('lembar-penilaian/{score}', 'App\Http\Controllers\PdfController@lembarPenilaian')->name('lembar-penilaian');
    Route::get('kartu-bimbingan/{application}', 'App\Http\Controllers\PdfController@kartuBimbingan')->name('kartu-bimbingan');
    Route::get('formulir-seminar/{seminar}', 'App\Http\Controllers\PdfController@formulirSeminar')->name('formulir-seminar');
    Route::get('formulir-sidang/{defense}', 'App\Http\Controllers\PdfController@formulirSidang')->name('formulir-sidang');
    Route::get('transkrip-nilai/{application}', 'App\Http\Controllers\PdfController@transkripNilai')->name('transkrip-nilai');
    Route::get('surat-keterangan-lulus/{application}', 'App\Http\Controllers\PdfController@suratKeteranganLulus')->name('surat-keterangan-lulus');
    // Preview for testing (can be removed in production)
    Route::get('preview', 'App\Http\Controllers\PdfController@preview')->name('preview');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/monitoring', 'HomeController@monitoring')->name('monitoring');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Article Category
    Route::delete('article-categories/destroy', 'ArticleCategoryController@massDestroy')->name('article-categories.massDestroy');
    Route::post('article-categories/parse-csv-import', 'ArticleCategoryController@parseCsvImport')->name('article-categories.parseCsvImport');
    Route::post('article-categories/process-csv-import', 'ArticleCategoryController@processCsvImport')->name('article-categories.processCsvImport');
    Route::resource('article-categories', 'ArticleCategoryController');

    // Article Tag
    Route::delete('article-tags/destroy', 'ArticleTagController@massDestroy')->name('article-tags.massDestroy');
    Route::post('article-tags/parse-csv-import', 'ArticleTagController@parseCsvImport')->name('article-tags.parseCsvImport');
    Route::post('article-tags/process-csv-import', 'ArticleTagController@processCsvImport')->name('article-tags.processCsvImport');
    Route::resource('article-tags', 'ArticleTagController');

    // Post
    Route::delete('posts/destroy', 'PostController@massDestroy')->name('posts.massDestroy');
    Route::post('posts/media', 'PostController@storeMedia')->name('posts.storeMedia');
    Route::post('posts/ckmedia', 'PostController@storeCKEditorImages')->name('posts.storeCKEditorImages');
    Route::post('posts/parse-csv-import', 'PostController@parseCsvImport')->name('posts.parseCsvImport');
    Route::post('posts/process-csv-import', 'PostController@processCsvImport')->name('posts.processCsvImport');
    Route::resource('posts', 'PostController');

    // Jenjang
    Route::delete('jenjangs/destroy', 'JenjangController@massDestroy')->name('jenjangs.massDestroy');
    Route::post('jenjangs/parse-csv-import', 'JenjangController@parseCsvImport')->name('jenjangs.parseCsvImport');
    Route::post('jenjangs/process-csv-import', 'JenjangController@processCsvImport')->name('jenjangs.processCsvImport');
    Route::resource('jenjangs', 'JenjangController');

    // Faculty
    Route::delete('faculties/destroy', 'FacultyController@massDestroy')->name('faculties.massDestroy');
    Route::post('faculties/parse-csv-import', 'FacultyController@parseCsvImport')->name('faculties.parseCsvImport');
    Route::post('faculties/process-csv-import', 'FacultyController@processCsvImport')->name('faculties.processCsvImport');
    Route::resource('faculties', 'FacultyController');

    // Prodi
    Route::delete('prodis/destroy', 'ProdiController@massDestroy')->name('prodis.massDestroy');
    Route::post('prodis/parse-csv-import', 'ProdiController@parseCsvImport')->name('prodis.parseCsvImport');
    Route::post('prodis/process-csv-import', 'ProdiController@processCsvImport')->name('prodis.processCsvImport');
    Route::resource('prodis', 'ProdiController');

    // Mahasiswa
    Route::delete('mahasiswas/destroy', 'MahasiswaController@massDestroy')->name('mahasiswas.massDestroy');
    Route::post('mahasiswas/parse-csv-import', 'MahasiswaController@parseCsvImport')->name('mahasiswas.parseCsvImport');
    Route::post('mahasiswas/process-csv-import', 'MahasiswaController@processCsvImport')->name('mahasiswas.processCsvImport');
    Route::resource('mahasiswas', 'MahasiswaController');

    // Dosen
    Route::delete('dosens/destroy', 'DosenController@massDestroy')->name('dosens.massDestroy');
    Route::post('dosens/parse-csv-import', 'DosenController@parseCsvImport')->name('dosens.parseCsvImport');
    Route::post('dosens/process-csv-import', 'DosenController@processCsvImport')->name('dosens.processCsvImport');
    Route::resource('dosens', 'DosenController');

    // Keilmuan
    Route::delete('keilmuans/destroy', 'KeilmuanController@massDestroy')->name('keilmuans.massDestroy');
    Route::post('keilmuans/parse-csv-import', 'KeilmuanController@parseCsvImport')->name('keilmuans.parseCsvImport');
    Route::post('keilmuans/process-csv-import', 'KeilmuanController@processCsvImport')->name('keilmuans.processCsvImport');
    Route::resource('keilmuans', 'KeilmuanController');

    // Research Group
    Route::delete('research-groups/destroy', 'ResearchGroupController@massDestroy')->name('research-groups.massDestroy');
    Route::post('research-groups/parse-csv-import', 'ResearchGroupController@parseCsvImport')->name('research-groups.parseCsvImport');
    Route::post('research-groups/process-csv-import', 'ResearchGroupController@processCsvImport')->name('research-groups.processCsvImport');
    Route::resource('research-groups', 'ResearchGroupController');

    // Application
    Route::delete('applications/destroy', 'ApplicationController@massDestroy')->name('applications.massDestroy');
    Route::post('applications/media', 'ApplicationController@storeMedia')->name('applications.storeMedia');
    Route::post('applications/ckmedia', 'ApplicationController@storeCKEditorImages')->name('applications.storeCKEditorImages');
    Route::resource('applications', 'ApplicationController');

    // Skripsi Dashboard
    Route::get('skripsi/dashboard', 'SkripsiDashboardController@index')->name('skripsi.dashboard');
    Route::get('skripsi/dashboard/data', 'SkripsiDashboardController@getData')->name('skripsi.dashboard.data');
    Route::get('skripsi/dashboard/chart-data', 'SkripsiDashboardController@getChartData')->name('skripsi.dashboard.chart-data');

    // Skripsi Defense
    Route::delete('skripsi-defenses/destroy', 'SkripsiDefenseController@massDestroy')->name('skripsi-defenses.massDestroy');
    Route::post('skripsi-defenses/media', 'SkripsiDefenseController@storeMedia')->name('skripsi-defenses.storeMedia');
    Route::post('skripsi-defenses/ckmedia', 'SkripsiDefenseController@storeCKEditorImages')->name('skripsi-defenses.storeCKEditorImages');
    Route::post('skripsi-defenses/{skripsi_defense}/assign-examiners', 'SkripsiDefenseController@assignExaminers')->name('skripsi-defenses.assign-examiners');
    Route::post('skripsi-defenses/{skripsi_defense}/accept', 'SkripsiDefenseController@accept')->name('skripsi-defenses.accept');
    Route::post('skripsi-defenses/{skripsi_defense}/reject', 'SkripsiDefenseController@reject')->name('skripsi-defenses.reject');
    Route::resource('skripsi-defenses', 'SkripsiDefenseController');

    // Skripsi Registration
    Route::delete('skripsi-registrations/destroy', 'SkripsiRegistrationController@massDestroy')->name('skripsi-registrations.massDestroy');
    Route::post('skripsi-registrations/media', 'SkripsiRegistrationController@storeMedia')->name('skripsi-registrations.storeMedia');
    Route::post('skripsi-registrations/ckmedia', 'SkripsiRegistrationController@storeCKEditorImages')->name('skripsi-registrations.storeCKEditorImages');
    Route::post('skripsi-registrations/{id}/approve', 'SkripsiRegistrationController@approve')->name('skripsi-registrations.approve');
    Route::post('skripsi-registrations/{id}/reject', 'SkripsiRegistrationController@reject')->name('skripsi-registrations.reject');
    Route::post('skripsi-registrations/{id}/request-revision', 'SkripsiRegistrationController@requestRevision')->name('skripsi-registrations.request-revision');
    Route::resource('skripsi-registrations', 'SkripsiRegistrationController');

    // Skripsi Seminar
    Route::delete('skripsi-seminars/destroy', 'SkripsiSeminarController@massDestroy')->name('skripsi-seminars.massDestroy');
    Route::post('skripsi-seminars/media', 'SkripsiSeminarController@storeMedia')->name('skripsi-seminars.storeMedia');
    Route::post('skripsi-seminars/ckmedia', 'SkripsiSeminarController@storeCKEditorImages')->name('skripsi-seminars.storeCKEditorImages');
    Route::post('skripsi-seminars/{id}/approve', 'SkripsiSeminarController@approve')->name('skripsi-seminars.approve');
    Route::post('skripsi-seminars/{id}/reject', 'SkripsiSeminarController@reject')->name('skripsi-seminars.reject');
    Route::resource('skripsi-seminars', 'SkripsiSeminarController');

    // Mbkm Registration
    Route::delete('mbkm-registrations/destroy', 'MbkmRegistrationController@massDestroy')->name('mbkm-registrations.massDestroy');
    Route::post('mbkm-registrations/media', 'MbkmRegistrationController@storeMedia')->name('mbkm-registrations.storeMedia');
    Route::post('mbkm-registrations/ckmedia', 'MbkmRegistrationController@storeCKEditorImages')->name('mbkm-registrations.storeCKEditorImages');
    Route::post('mbkm-registrations/{id}/approve', 'MbkmRegistrationController@approve')->name('mbkm-registrations.approve');
    Route::post('mbkm-registrations/{id}/reject', 'MbkmRegistrationController@reject')->name('mbkm-registrations.reject');
    Route::post('mbkm-registrations/{id}/request-revision', 'MbkmRegistrationController@requestRevision')->name('mbkm-registrations.request-revision');
    Route::resource('mbkm-registrations', 'MbkmRegistrationController');

    // Mbkm Group Member
    Route::delete('mbkm-group-members/destroy', 'MbkmGroupMemberController@massDestroy')->name('mbkm-group-members.massDestroy');
    Route::resource('mbkm-group-members', 'MbkmGroupMemberController');

    // Mbkm Seminar
    Route::delete('mbkm-seminars/destroy', 'MbkmSeminarController@massDestroy')->name('mbkm-seminars.massDestroy');
    Route::post('mbkm-seminars/media', 'MbkmSeminarController@storeMedia')->name('mbkm-seminars.storeMedia');
    Route::post('mbkm-seminars/ckmedia', 'MbkmSeminarController@storeCKEditorImages')->name('mbkm-seminars.storeCKEditorImages');
    Route::post('mbkm-seminars/{mbkm_seminar}/approve', 'MbkmSeminarController@approve')->name('mbkm-seminars.approve');
    Route::post('mbkm-seminars/{mbkm_seminar}/reject', 'MbkmSeminarController@reject')->name('mbkm-seminars.reject');
    Route::resource('mbkm-seminars', 'MbkmSeminarController');

    // Application Report
    Route::delete('application-reports/destroy', 'ApplicationReportController@massDestroy')->name('application-reports.massDestroy');
    Route::post('application-reports/media', 'ApplicationReportController@storeMedia')->name('application-reports.storeMedia');
    Route::post('application-reports/ckmedia', 'ApplicationReportController@storeCKEditorImages')->name('application-reports.storeCKEditorImages');
    Route::post('application-reports/{application_report}/mark-reviewed', 'ApplicationReportController@markAsReviewed')->name('application-reports.mark-reviewed');
    Route::resource('application-reports', 'ApplicationReportController');

    // Application Assignment
    Route::delete('application-assignments/destroy', 'ApplicationAssignmentController@massDestroy')->name('application-assignments.massDestroy');
    Route::resource('application-assignments', 'ApplicationAssignmentController');

    // Application Result Seminar
    Route::post('application-result-seminars/{id}/approve', 'ApplicationResultSeminarController@approve')->name('application-result-seminars.approve');
    Route::post('application-result-seminars/{id}/reject', 'ApplicationResultSeminarController@reject')->name('application-result-seminars.reject');
    Route::delete('application-result-seminars/destroy', 'ApplicationResultSeminarController@massDestroy')->name('application-result-seminars.massDestroy');
    Route::post('application-result-seminars/media', 'ApplicationResultSeminarController@storeMedia')->name('application-result-seminars.storeMedia');
    Route::post('application-result-seminars/ckmedia', 'ApplicationResultSeminarController@storeCKEditorImages')->name('application-result-seminars.storeCKEditorImages');
    Route::resource('application-result-seminars', 'ApplicationResultSeminarController');

    // Application Result Review
    Route::post('application-result-reviews/{id}/approve', 'ApplicationResultReviewController@approve')->name('application-result-reviews.approve');
    Route::post('application-result-reviews/{id}/reject', 'ApplicationResultReviewController@reject')->name('application-result-reviews.reject');
    Route::delete('application-result-reviews/destroy', 'ApplicationResultReviewController@massDestroy')->name('application-result-reviews.massDestroy');
    Route::post('application-result-reviews/media', 'ApplicationResultReviewController@storeMedia')->name('application-result-reviews.storeMedia');
    Route::post('application-result-reviews/ckmedia', 'ApplicationResultReviewController@storeCKEditorImages')->name('application-result-reviews.storeCKEditorImages');
    Route::resource('application-result-reviews', 'ApplicationResultReviewController');

    // Application Result Defense
    Route::delete('application-result-defenses/destroy', 'ApplicationResultDefenseController@massDestroy')->name('application-result-defenses.massDestroy');
    Route::post('application-result-defenses/media', 'ApplicationResultDefenseController@storeMedia')->name('application-result-defenses.storeMedia');
    Route::post('application-result-defenses/ckmedia', 'ApplicationResultDefenseController@storeCKEditorImages')->name('application-result-defenses.storeCKEditorImages');
    Route::get('application-result-defenses/{application_result_defense}/print-score', 'ApplicationResultDefenseController@printScore')->name('application-result-defenses.print-score');
    Route::resource('application-result-defenses', 'ApplicationResultDefenseController');

    // Application Score
    Route::delete('application-scores/destroy', 'ApplicationScoreController@massDestroy')->name('application-scores.massDestroy');
    Route::resource('application-scores', 'ApplicationScoreController');

    // Application Schedule
    Route::delete('application-schedules/destroy', 'ApplicationScheduleController@massDestroy')->name('application-schedules.massDestroy');
    Route::post('application-schedules/media', 'ApplicationScheduleController@storeMedia')->name('application-schedules.storeMedia');
    Route::post('application-schedules/ckmedia', 'ApplicationScheduleController@storeCKEditorImages')->name('application-schedules.storeCKEditorImages');
    Route::post('application-schedules/{id}/approve', 'ApplicationScheduleController@approve')->name('application-schedules.approve');
    Route::post('application-schedules/{id}/reject', 'ApplicationScheduleController@reject')->name('application-schedules.reject');
    Route::resource('application-schedules', 'ApplicationScheduleController');

    // Ruang
    Route::delete('ruangs/destroy', 'RuangController@massDestroy')->name('ruangs.massDestroy');
    Route::post('ruangs/media', 'RuangController@storeMedia')->name('ruangs.storeMedia');
    Route::post('ruangs/ckmedia', 'RuangController@storeCKEditorImages')->name('ruangs.storeCKEditorImages');
    Route::post('ruangs/parse-csv-import', 'RuangController@parseCsvImport')->name('ruangs.parseCsvImport');
    Route::post('ruangs/process-csv-import', 'RuangController@processCsvImport')->name('ruangs.processCsvImport');
    Route::resource('ruangs', 'RuangController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    // Profile
    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');

    // Mahasiswa Profile
    Route::get('frontend/mahasiswa-profile/create', 'ProfileController@createMahasiswaProfile')->name('mahasiswa-profile.create');
    Route::post('frontend/mahasiswa-profile/create', 'ProfileController@storeMahasiswaProfile')->name('mahasiswa-profile.store');
    Route::get('frontend/mahasiswa-profile', 'ProfileController@editMahasiswaProfile')->name('mahasiswa-profile.edit');
    Route::post('frontend/mahasiswa-profile', 'ProfileController@updateMahasiswaProfile')->name('mahasiswa-profile.update');

    // Dosen Profile
    Route::get('frontend/dosen-profile/create', 'ProfileController@createDosenProfile')->name('dosen-profile.create');
    Route::post('frontend/dosen-profile/create', 'ProfileController@storeDosenProfile')->name('dosen-profile.store');
    Route::get('frontend/dosen-profile', 'ProfileController@editDosenProfile')->name('dosen-profile.edit');
    Route::post('frontend/dosen-profile', 'ProfileController@updateDosenProfile')->name('dosen-profile.update');
    
    // Path Selection - Choose between Skripsi Reguler or MBKM
    Route::get('frontend/choose-path', 'PathSelectionController@index')->name('choose-path');
    Route::post('frontend/choose-path', 'PathSelectionController@store')->name('choose-path.store');
    
    // Applications - General application routes
    Route::get('frontend/applications', 'ApplicationController@index')->name('applications.index');
    Route::get('frontend/applications/{application}', 'ApplicationController@show')->name('applications.show');
    
    // Skripsi Reguler Routes
    Route::prefix('frontend/skripsi')->group(function () {
        Route::get('/', 'SkripsiRegistrationController@index')->name('skripsi.index');
        Route::get('/{application}/create', 'SkripsiRegistrationController@create')->name('skripsi.create');
        Route::post('/{application}/store', 'SkripsiRegistrationController@store')->name('skripsi.store');
        Route::get('/{application}', 'SkripsiRegistrationController@show')->name('skripsi.show');
        Route::get('/{application}/edit', 'SkripsiRegistrationController@edit')->name('skripsi.edit');
        Route::put('/{application}', 'SkripsiRegistrationController@update')->name('skripsi.update');
    });
    
    // Skripsi Registration Alias Routes (for backward compatibility)
    Route::prefix('frontend/skripsi-registrations')->group(function () {
        Route::get('/', 'SkripsiRegistrationController@index')->name('skripsi-registrations.index');
        Route::get('/create', 'SkripsiRegistrationController@create')->name('skripsi-registrations.create');
        Route::post('/', 'SkripsiRegistrationController@store')->name('skripsi-registrations.store');
        Route::get('/{id}', 'SkripsiRegistrationController@show')->name('skripsi-registrations.show');
        Route::get('/{id}/edit', 'SkripsiRegistrationController@edit')->name('skripsi-registrations.edit');
        Route::put('/{id}', 'SkripsiRegistrationController@update')->name('skripsi-registrations.update');
        Route::delete('/{id}', 'SkripsiRegistrationController@destroy')->name('skripsi-registrations.destroy');
        Route::delete('/destroy', 'SkripsiRegistrationController@massDestroy')->name('skripsi-registrations.massDestroy');
        Route::post('/media', 'SkripsiRegistrationController@storeMedia')->name('skripsi-registrations.storeMedia');
    });
    
    // MBKM Routes
    Route::prefix('frontend/mbkm')->group(function () {
        Route::get('/', 'MbkmRegistrationController@index')->name('mbkm.index');
        Route::get('/{application}/create', 'MbkmRegistrationController@create')->name('mbkm.create');
        Route::post('/{application}/store', 'MbkmRegistrationController@store')->name('mbkm.store');
        Route::get('/{application}', 'MbkmRegistrationController@show')->name('mbkm.show');
        Route::get('/{application}/edit', 'MbkmRegistrationController@edit')->name('mbkm.edit');
        Route::put('/{application}', 'MbkmRegistrationController@update')->name('mbkm.update');
    });
    
    // MBKM Registration Alias Routes (for backward compatibility)
    Route::prefix('frontend/mbkm-registrations')->group(function () {
        Route::get('/', 'MbkmRegistrationController@index')->name('mbkm-registrations.index');
        Route::get('/create', 'MbkmRegistrationController@create')->name('mbkm-registrations.create');
        Route::post('/', 'MbkmRegistrationController@store')->name('mbkm-registrations.store');
        Route::get('/{id}', 'MbkmRegistrationController@show')->name('mbkm-registrations.show');
        Route::get('/{id}/edit', 'MbkmRegistrationController@edit')->name('mbkm-registrations.edit');
        Route::put('/{id}', 'MbkmRegistrationController@update')->name('mbkm-registrations.update');
        Route::delete('/{id}', 'MbkmRegistrationController@destroy')->name('mbkm-registrations.destroy');
        Route::delete('/destroy', 'MbkmRegistrationController@massDestroy')->name('mbkm-registrations.massDestroy');
        Route::post('/media', 'MbkmRegistrationController@storeMedia')->name('mbkm-registrations.storeMedia');
    });
    
    // Skripsi Seminar Routes
    Route::delete('skripsi-seminars/destroy', 'SkripsiSeminarController@massDestroy')->name('skripsi-seminars.massDestroy');
    Route::post('skripsi-seminars/media', 'SkripsiSeminarController@storeMedia')->name('skripsi-seminars.storeMedia');
    Route::post('skripsi-seminars/ckmedia', 'SkripsiSeminarController@storeCKEditorImages')->name('skripsi-seminars.storeCKEditorImages');
    Route::resource('skripsi-seminars', 'SkripsiSeminarController');
    
    // MBKM Seminar Routes
    Route::delete('mbkm-seminars/destroy', 'MbkmSeminarController@massDestroy')->name('mbkm-seminars.massDestroy');
    Route::post('mbkm-seminars/media', 'MbkmSeminarController@storeMedia')->name('mbkm-seminars.storeMedia');
    Route::post('mbkm-seminars/ckmedia', 'MbkmSeminarController@storeCKEditorImages')->name('mbkm-seminars.storeCKEditorImages');
    Route::resource('mbkm-seminars', 'MbkmSeminarController');
    
    // Skripsi Defense Routes
    Route::delete('skripsi-defenses/destroy', 'SkripsiDefenseController@massDestroy')->name('skripsi-defenses.massDestroy');
    Route::post('skripsi-defenses/media', 'SkripsiDefenseController@storeMedia')->name('skripsi-defenses.storeMedia');
    Route::post('skripsi-defenses/ckmedia', 'SkripsiDefenseController@storeCKEditorImages')->name('skripsi-defenses.storeCKEditorImages');
    Route::resource('skripsi-defenses', 'SkripsiDefenseController');

    // Laporan Hasil Review Proposal (Skripsi Reguler)
    Route::post('application-result-seminars/media', 'ApplicationResultSeminarController@storeMedia')->name('application-result-seminars.storeMedia');
    Route::resource('application-result-seminars', 'ApplicationResultSeminarController')->only([
        'index', 'create', 'store', 'show',
    ]);

    // Laporan Kendala (opsional, kapan saja selama proses skripsi/MBKM)
    Route::post('application-reports/media', 'ApplicationReportController@storeMedia')->name('application-reports.storeMedia');
    Route::post('application-reports/ckmedia', 'ApplicationReportController@storeCKEditorImages')->name('application-reports.storeCKEditorImages');
    Route::resource('application-reports', 'ApplicationReportController')->only([
        'index', 'create', 'store', 'show',
    ]);

    // Jadwal seminar / sidang (mahasiswa ajukan, admin verifikasi)
    Route::resource('application-schedules', 'ApplicationScheduleController')->only([
        'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
    ]);
});

Route::group(['prefix' => 'dosen', 'as' => 'dosen.', 'namespace' => 'Dosen', 'middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/mahasiswa-bimbingan', 'DashboardController@mahasiswaBimbingan')->name('mahasiswa-bimbingan');
    Route::get('/task-assignments', 'DashboardController@taskAssignments')->name('task-assignments');
    Route::get('/task-assignments/{assignment}/review', 'DashboardController@reviewProposal')->name('review-proposal');
    Route::post('/task-assignments/{assignment}/respond', 'DashboardController@respondToAssignment')->name('task-assignments.respond');
    Route::get('/scores', 'DashboardController@scores')->name('scores');
    Route::get('/scoring/{application}', 'DashboardController@scoring')->name('scoring');
    Route::post('/scoring/{application}', 'DashboardController@storeScoring')->name('scoring.store');
    Route::get('/profile', 'DashboardController@profile')->name('profile');
});

Route::group(['prefix' => 'mahasiswa', 'as' => 'mahasiswa.', 'namespace' => 'Mahasiswa', 'middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/aplikasi', 'DashboardController@aplikasi')->name('aplikasi');
    Route::get('/bimbingan', 'DashboardController@bimbingan')->name('bimbingan');
    Route::get('/jadwal', 'DashboardController@jadwal')->name('jadwal');
    Route::get('/dokumen', 'DashboardController@dokumen')->name('dokumen');
    Route::get('/profile', 'DashboardController@profile')->name('profile');
});
