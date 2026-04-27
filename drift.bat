@echo off
echo =======================================================
echo          BSMF GARAGE - QUICK START (DRIFT MODE)
echo =======================================================

:: 1. Try to start MySQL if it's not running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo [!] MySQL is off. Attempting to ignite...
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        timeout /t 5 >nul
    ) else (
        echo [!] Could not find XAMPP mysql_start.bat. Please start MySQL manually.
        pause
        exit /b
    )
) else (
    echo [OK] MySQL Engine is already purring.
)

:: 2. Clear Cache (Optional but helpful)
echo [OK] Clearing track debris (cache)...
php artisan config:clear >nul
php artisan view:clear >nul

:: 3. Launch
echo.
echo Launching the garage at http://127.0.0.1:8000...
echo.
php artisan serve
