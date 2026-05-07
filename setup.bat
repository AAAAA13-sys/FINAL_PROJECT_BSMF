@echo off
setlocal enabledelayedexpansion
title BSMF GARAGE - SETUP
color 0B

echo [BSMF] Initializing Setup...

:: 0. Environment Checks
echo [0/8] Checking System Prerequisites...

:: Check PHP
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP not found in PATH.
    if exist "C:\xampp\php\php.exe" (
        echo [INFO] Found PHP in XAMPP. Adding to temporary PATH...
        set "PATH=%PATH%;C:\xampp\php"
    ) else (
        echo [FATAL] PHP is required but not found. Please install XAMPP or PHP.
        pause
        exit /b 1
    )
)

:: Check Composer
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] Composer not found. Please install Composer.
    pause
    exit /b 1
)

:: Check NPM
where npm >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] NPM not found. Please install Node.js.
    pause
    exit /b 1
)

:: 1. .env Verification
echo [1/8] Verifying configuration files...
if not exist .env (
    echo [ALERT] .env missing. Cloning from example...
    copy .env.example .env >nul
    echo [OK] Configuration initialized.
) else (
    echo [SKIP] Configuration already active.
)

:: 2. MySQL Status
echo [2/8] Ensuring Database Engine is running...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo [INFO] MySQL is not running. Attempting to start...
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        echo [WAIT] Waiting for MySQL to initialize (5s)...
        timeout /t 5 >nul
    ) else (
        echo [WARNING] Could not auto-start MySQL. Please start it via XAMPP Control Panel.
    )
) else (
    echo [OK] MySQL is active.
)

:: 3. Database Creation
echo [3/8] Preparing database schema...
:: Use a PHP snippet to create the database if it doesn't exist
php -r "$e=file_exists('.env')?parse_ini_file('.env'):[]; $db=$e['DB_DATABASE']??'final_project_bsmf'; $u=$e['DB_USERNAME']??'root'; $p=$e['DB_PASSWORD']??''; try { $pdo = new PDO('mysql:host=127.0.0.1', $u, $p); $pdo->exec(\"CREATE DATABASE IF NOT EXISTS `$db`\"); echo \"[OK] Database `$db` ready.\n\"; } catch (Exception $ex) { echo \"[WARNING] Auto-creation failed: \" . $ex->getMessage() . \"\n\"; }"

:: 4. Dependencies
echo [4/8] Synchronizing PHP dependencies (Composer)...
call composer install --no-interaction
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] Composer synchronization failed.
    pause
    exit /b %ERRORLEVEL%
)
echo [OK] Backend synchronized.

:: 5. App Key & Database Refresh
echo [5/8] Finalizing application state...
call php artisan key:generate --force
call php artisan migrate:fresh --seed --force
if %ERRORLEVEL% NEQ 0 (
    echo [FATAL] Migration failed. Check your DB credentials in .env.
    pause
    exit /b %ERRORLEVEL%
)
echo [OK] Database is online and seeded.

:: 6. Assets & Storage
echo [6/8] Linking storage assets...
if exist public\storage (
    rmdir /s /q public\storage 2>&1
)
call php artisan storage:link
echo [OK] Asset links established.

:: 7. Frontend Build
echo [7/8] Compiling frontend assets (NPM)...
if not exist node_modules (
    echo [INFO] Installing Node modules (this may take a while)...
    call npm install
)
echo [INFO] Building production assets...
call npm run build
if %ERRORLEVEL% NEQ 0 (
    echo [WARNING] Frontend build failed. Check for errors above.
)
echo [OK] UI Engine compiled.

:: 8. Cache Cleanup
echo [8/8] Optimizing system performance...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear
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
