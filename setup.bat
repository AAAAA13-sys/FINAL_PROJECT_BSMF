@echo off
setlocal enabledelayedexpansion
title BSMF GARAGE - SETUP
color 0C

echo [BSMF] Initializing Setup...

:: 1. .env Verification
echo [1/8] Verifying configuration files...
if not exist .env (
    echo [ALERT] .env missing. Cloning from example...
    copy .env.example .env >nul
    echo [OK] Configuration initialized.
) else (
    echo [SKIP] Configuration already active.
)

:: 2. Dependencies
echo [2/8] Synchronizing PHP dependencies (Composer)...
call composer install --no-interaction
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] Composer synchronization failed.
    pause
    exit /b %ERRORLEVEL%
)
echo [OK] Backend synchronized.

:: 3. App Key
echo [3/8] Generating security signatures...
call php artisan key:generate --force
echo [OK] Encryption keys generated.

:: 4. Database Setup
echo [4/8] Rebuilding database architecture...
echo       (Refreshing all tables and seeding collector data)
call php artisan migrate:fresh --seed --force
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] Database migration failed. 
    echo         Please ensure MySQL is running and 'final_project_bsmf' exists.
    pause
    exit /b %ERRORLEVEL%
)
echo [OK] Database is online.

:: 5. Optimization
echo [5/8] Optimizing system cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear
echo [OK] Cache purged.

:: 6. Assets
echo [6/8] Linking storage assets...
if exist public\storage (
    rmdir /s /q public\storage 2>&1
)
call php artisan storage:link
echo [OK] Asset links established.

:: 7. Frontend Build
echo [7/8] Compiling frontend assets (NPM)...
if not exist node_modules (
    echo [INFO] Installing Node modules...
    call npm install
)
echo [INFO] Building production assets...
call npm run build
echo [OK] UI Engine compiled.

:: 8. Final Checks
echo [8/8] Finalizing deployment...
ping -n 2 127.0.0.1 >nul
echo [OK] System is nominal.

echo.
echo ===============================================================
echo          SETUP COMPLETE - BSMF GARAGE IS READY
echo ===============================================================
echo.
echo  ADMIN ACCESS:
echo  Email: admin@bsmfgarage.com
echo  Pass:  password
echo.
echo  STAFF ACCESS:
echo  Email: staff@bsmfgarage.com
echo  Pass:  password
echo.
echo  Launching Collector Hub at http://127.0.0.1:8000
echo  (Stay tuned. Press Ctrl+C to stop the engine.)
echo.

php artisan serve
