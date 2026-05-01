@echo off
setlocal enabledelayedexpansion

echo =======================================================
echo          BSMF GARAGE - AUTOMATED SETUP
echo =======================================================
echo.

:: 1. Check for .env file
if not exist .env (
    echo [1/6] Creating .env from .env.example...
    copy .env.example .env
) else (
    echo [1/6] .env already exists, skipping...
)

:: 2. Install PHP Dependencies
echo [2/6] Installing Composer dependencies...
call composer install

:: 3. Generate Application Key
echo [3/6] Generating application key...
call php artisan key:generate

:: 4. Run Migrations and Seed Database
echo [4/7] Setting up database (Migrating and Seeding)...
echo        Ensure you have created the database 'final_project_bsmf' in XAMPP/MySQL.
call php artisan migrate:fresh --seed
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Migration failed. Check your database connection.
    pause
    exit /b %ERRORLEVEL%
)

:: 5. Clear Caches
echo [5/7] Clearing application cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear

:: 6. Create Storage Link
echo [6/7] Linking storage...
call php artisan storage:link

:: 7. Install Node Dependencies
if exist package.json (
    echo [7/7] Installing NPM dependencies...
    call npm install
)

echo.
echo =======================================================
echo          SETUP COMPLETE! READY FOR DRIFTING.
echo =======================================================
echo.
echo  ADMIN CREDENTIALS:
echo  Email: admin@bsmfgarage.com
echo  Pass:  password
echo.
echo  STAFF CREDENTIALS:
echo  Email: staff@bsmfgarage.com
echo  Pass:  password
echo.
echo Launching the server at http://127.0.0.1:8000...
echo (Press Ctrl+C to stop the server)
echo.
php artisan serve
