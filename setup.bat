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
echo [4/6] Setting up database (Migrating and Seeding)...
echo        Ensure you have created the database 'final_project_bsmf' in XAMPP/MySQL.
call php artisan migrate:fresh --seed

:: 5. Create Storage Link
echo [5/6] Linking storage...
call php artisan storage:link

:: 6. Install Node Dependencies
if exist package.json (
    echo [6/7] Installing NPM dependencies...
    call npm install
)

:: 7. Populate Product Images
if exist scratch\populate_images.php (
    echo [7/7] Populating product images...
    call php scratch\populate_images.php
)

echo.
echo =======================================================
echo          SETUP COMPLETE! READY FOR DRIFTING.
echo =======================================================
echo.
echo Launching the server at http://127.0.0.1:8000...
echo (Press Ctrl+C to stop the server)
echo.
php artisan serve
