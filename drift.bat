@echo off

:: Ensure MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        timeout /t 2 >nul
    )
)

:: Clear caches just in case
call php artisan config:clear >nul
call php artisan view:clear >nul

:: Start background services
start /min "BSMF Assets" npm run dev
start /min "BSMF Queue" php artisan queue:work

:: Open application
timeout /t 3 >nul
start "" "http://127.0.0.1:8000"

:: Start main server
php artisan serve
