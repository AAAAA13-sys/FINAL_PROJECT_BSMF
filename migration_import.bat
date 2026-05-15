@echo off
setlocal enabledelayedexpansion
title BSMF GARAGE - MIGRATION IMPORT
color 0B

echo.
echo  ================================================
echo    BSMF GARAGE - DATABASE RESTORATION SCRIPT
echo  ================================================
echo.

:: 1. Check for MySQL
echo [1/5] Checking MySQL status...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo [!] MySQL is not running. Attempting to start XAMPP MySQL...
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        timeout /t 5 >nul
    ) else (
        echo [X] Error: Could not find C:\xampp\mysql_start.bat
        echo Please start MySQL manually and run this script again.
        pause
        exit /b
    )
) else (
    echo [OK] MySQL is running.
)

:: 2. Environment Setup
echo [2/5] Setting up environment...
if not exist .env (
    if exist .env.example (
        copy .env.example .env >nul
        echo [OK] Created .env from .env.example
    ) else (
        echo [X] Error: .env.example not found.
    )
)

:: 3. Database Creation and Import
echo [3/5] Importing database dump...
if not exist final_project_bsmf.sql (
    echo [X] Error: final_project_bsmf.sql not found in this directory!
    pause
    exit /b
)

:: Use PHP to extract DB info from .env and perform the import
php -r "
$env = file_exists('.env') ? parse_ini_file('.env') : [];
$db = $env['DB_DATABASE'] ?? 'final_project_bsmf';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$host = $env['DB_HOST'] ?? '127.0.0.1';

try {
    $pdo = new PDO(\"mysql:host=$host\", $user, $pass);
    $pdo->exec(\"CREATE DATABASE IF NOT EXISTS `$db`\");
    echo \"[OK] Database '$db' ensured.\n\";
    
    echo \"[!] Importing data... Please wait...\n\";
    // Using command line mysql for large imports
    $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';
    if (!file_exists($mysqlPath)) $mysqlPath = 'mysql';
    
    $cmd = sprintf('\"%s\" -u %s %s %s < final_project_bsmf.sql', 
        $mysqlPath, 
        $user, 
        ($pass ? '-p' . $pass : ''), 
        $db
    );
    
    system($cmd, $retval);
    if ($retval === 0) {
        echo \"[OK] Database successfully restored from final_project_bsmf.sql\n\";
    } else {
        echo \"[X] Import failed with exit code $retval\n\";
    }
} catch (Exception $e) {
    echo \"[X] Connection Error: \" . $e->getMessage() . \"\n\";
}
"

:: 4. Dependencies
echo [4/5] Installing dependencies...
if not exist vendor (
    echo [!] Vendor folder missing. Running composer install...
    call composer install --no-interaction
)
if not exist node_modules (
    echo [!] node_modules missing. Running npm install...
    call npm install
)

:: 5. Finalizing
echo [5/5] Finalizing application state...
call php artisan key:generate --force
if exist public\storage rmdir /s /q public\storage
call php artisan storage:link
call php artisan config:clear
call php artisan cache:clear

echo.
echo  ================================================
echo    SETUP COMPLETE! 
echo    Your system has been migrated with existing data.
echo  ================================================
echo.
echo  Starting local server...
start "" "http://127.0.0.1:8000"
php artisan serve
