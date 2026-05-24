@echo off
REM ===============================================
REM Thesis Management System - Quick Test Runner
REM ===============================================

echo.
echo ============================================
echo   THESIS MANAGEMENT SYSTEM - TEST RUNNER
echo ============================================
echo.

:MENU
echo Choose testing option:
echo.
echo [1] Setup Test Environment (Migrate + Seed)
echo [2] Run Automated Feature Tests
echo [3] Start Development Server
echo [4] Full Test Setup + Run Tests
echo [5] View Test Results Summary
echo [6] Clear All Caches
echo [7] Exit
echo.

set /p choice="Enter your choice (1-7): "

if "%choice%"=="1" goto SETUP
if "%choice%"=="2" goto RUN_TESTS
if "%choice%"=="3" goto START_SERVER
if "%choice%"=="4" goto FULL_TEST
if "%choice%"=="5" goto VIEW_RESULTS
if "%choice%"=="6" goto CLEAR_CACHE
if "%choice%"=="7" goto EXIT
goto MENU

:SETUP
echo.
echo ============================================
echo   Setting up test environment...
echo ============================================
echo.
echo [1/3] Running migrations...
php artisan migrate:fresh
echo.
echo [2/3] Seeding test data...
php artisan db:seed --class=TestDataSeeder
echo.
echo [3/3] Creating storage symlink...
php artisan storage:link
echo.
echo ============================================
echo   Setup complete!
echo ============================================
echo.
echo Test Accounts Created:
echo   Admin:      admin@test.com / password
echo   Dosen 1-3:  dosen1@test.com - dosen3@test.com / password
echo   Mahasiswa 1-3: mahasiswa1@test.com - mahasiswa3@test.com / password
echo.
pause
goto MENU

:RUN_TESTS
echo.
echo ============================================
echo   Running automated tests...
echo ============================================
echo.
php artisan test --filter=ThesisWorkflowTest
echo.
echo ============================================
echo   Tests complete!
echo ============================================
echo.
pause
goto MENU

:START_SERVER
echo.
echo ============================================
echo   Starting development server...
echo ============================================
echo.
echo Server will start at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
php artisan serve
pause
goto MENU

:FULL_TEST
echo.
echo ============================================
echo   Full Test Setup + Execution
echo ============================================
echo.
echo [1/5] Clearing caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.
echo [2/5] Running migrations...
php artisan migrate:fresh
echo.
echo [3/5] Seeding test data...
php artisan db:seed --class=TestDataSeeder
echo.
echo [4/5] Creating storage symlink...
php artisan storage:link
echo.
echo [5/5] Running automated tests...
php artisan test --filter=ThesisWorkflowTest
echo.
echo ============================================
echo   Full test complete!
echo ============================================
echo.
echo Next Steps:
echo 1. Start server: Run option [3]
echo 2. Open browser: http://localhost:8000
echo 3. Follow MANUAL_TESTING_CHECKLIST.md
echo.
pause
goto MENU

:VIEW_RESULTS
echo.
echo ============================================
echo   Test Results Summary
echo ============================================
echo.
if exist TEST_RESULTS.md (
    type TEST_RESULTS.md
) else (
    echo TEST_RESULTS.md not found.
    echo Run tests first to generate results.
)
echo.
pause
goto MENU

:CLEAR_CACHE
echo.
echo ============================================
echo   Clearing all caches...
echo ============================================
echo.
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.
echo Caches cleared successfully!
echo.
pause
goto MENU

:EXIT
echo.
echo Goodbye!
echo.
exit

REM ===============================================
REM End of Test Runner Script
REM ===============================================
