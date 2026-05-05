@echo off
title BSMF GARAGE - QUICK START
color 0C

echo [BSMF] Checking Engine...

:: 1. MySQL Check
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo [!] MySQL is off. Starting...
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        timeout /t 5 >nul
    ) else (
        echo [!] MySQL Start script not found. Start it manually!
        pause
        exit /b
    )
)

:: 2. Cache Clear
echo [BSMF] Clearing Track Debris...
php artisan config:clear >nul
php artisan view:clear >nul

:: 3. Launch
echo [BSMF] Launching at http://127.0.0.1:8000
php artisan serve
