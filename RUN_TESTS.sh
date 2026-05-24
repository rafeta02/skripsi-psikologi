#!/bin/bash
# ===============================================
# Thesis Management System - Quick Test Runner
# ===============================================

clear

echo ""
echo "============================================"
echo "  THESIS MANAGEMENT SYSTEM - TEST RUNNER"
echo "============================================"
echo ""

function show_menu() {
    echo "Choose testing option:"
    echo ""
    echo "[1] Setup Test Environment (Migrate + Seed)"
    echo "[2] Run Automated Feature Tests"
    echo "[3] Start Development Server"
    echo "[4] Full Test Setup + Run Tests"
    echo "[5] View Test Results Summary"
    echo "[6] Clear All Caches"
    echo "[7] Exit"
    echo ""
    read -p "Enter your choice (1-7): " choice
    
    case $choice in
        1) setup_env ;;
        2) run_tests ;;
        3) start_server ;;
        4) full_test ;;
        5) view_results ;;
        6) clear_caches ;;
        7) exit 0 ;;
        *) show_menu ;;
    esac
}

function setup_env() {
    echo ""
    echo "============================================"
    echo "  Setting up test environment..."
    echo "============================================"
    echo ""
    
    echo "[1/3] Running migrations..."
    php artisan migrate:fresh
    echo ""
    
    echo "[2/3] Seeding test data..."
    php artisan db:seed --class=TestDataSeeder
    echo ""
    
    echo "[3/3] Creating storage symlink..."
    php artisan storage:link
    echo ""
    
    echo "============================================"
    echo "  Setup complete!"
    echo "============================================"
    echo ""
    echo "Test Accounts Created:"
    echo "  Admin:      admin@test.com / password"
    echo "  Dosen 1-3:  dosen1@test.com - dosen3@test.com / password"
    echo "  Mahasiswa 1-3: mahasiswa1@test.com - mahasiswa3@test.com / password"
    echo ""
    
    read -p "Press Enter to continue..."
    show_menu
}

function run_tests() {
    echo ""
    echo "============================================"
    echo "  Running automated tests..."
    echo "============================================"
    echo ""
    
    php artisan test --filter=ThesisWorkflowTest
    
    echo ""
    echo "============================================"
    echo "  Tests complete!"
    echo "============================================"
    echo ""
    
    read -p "Press Enter to continue..."
    show_menu
}

function start_server() {
    echo ""
    echo "============================================"
    echo "  Starting development server..."
    echo "============================================"
    echo ""
    echo "Server will start at: http://localhost:8000"
    echo "Press Ctrl+C to stop the server"
    echo ""
    
    php artisan serve
    
    show_menu
}

function full_test() {
    echo ""
    echo "============================================"
    echo "  Full Test Setup + Execution"
    echo "============================================"
    echo ""
    
    echo "[1/5] Clearing caches..."
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    echo ""
    
    echo "[2/5] Running migrations..."
    php artisan migrate:fresh
    echo ""
    
    echo "[3/5] Seeding test data..."
    php artisan db:seed --class=TestDataSeeder
    echo ""
    
    echo "[4/5] Creating storage symlink..."
    php artisan storage:link
    echo ""
    
    echo "[5/5] Running automated tests..."
    php artisan test --filter=ThesisWorkflowTest
    echo ""
    
    echo "============================================"
    echo "  Full test complete!"
    echo "============================================"
    echo ""
    echo "Next Steps:"
    echo "1. Start server: Run option [3]"
    echo "2. Open browser: http://localhost:8000"
    echo "3. Follow MANUAL_TESTING_CHECKLIST.md"
    echo ""
    
    read -p "Press Enter to continue..."
    show_menu
}

function view_results() {
    echo ""
    echo "============================================"
    echo "  Test Results Summary"
    echo "============================================"
    echo ""
    
    if [ -f "TEST_RESULTS.md" ]; then
        cat TEST_RESULTS.md
    else
        echo "TEST_RESULTS.md not found."
        echo "Run tests first to generate results."
    fi
    echo ""
    
    read -p "Press Enter to continue..."
    show_menu
}

function clear_caches() {
    echo ""
    echo "============================================"
    echo "  Clearing all caches..."
    echo "============================================"
    echo ""
    
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    echo ""
    echo "Caches cleared successfully!"
    echo ""
    
    read -p "Press Enter to continue..."
    show_menu
}

# Start the menu
show_menu

# ===============================================
# End of Test Runner Script
# ===============================================
