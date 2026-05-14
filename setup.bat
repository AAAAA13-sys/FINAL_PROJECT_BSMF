@echo off
setlocal
title BSMF GARAGE - SETUP

:: Ensure MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    if exist "C:\xampp\mysql_start.bat" (
        start /min "" "C:\xampp\mysql_start.bat"
        timeout /t 2 >nul
    )
)

:: Configuration
if not exist .env copy .env.example .env >nul

:: Database Creation
php -r "$e=file_exists('.env')?parse_ini_file('.env'):[]; $db=$e['DB_DATABASE']??'final_project_bsmf'; $u=$e['DB_USERNAME']??'root'; $p=$e['DB_PASSWORD']??''; try { $pdo = new PDO('mysql:host=127.0.0.1', $u, $p); $pdo->exec(\"CREATE DATABASE IF NOT EXISTS `$db`\"); } catch (Exception $ex) {}"

:: Backend Dependencies
if exist vendor rmdir /s /q vendor
call composer install --no-interaction -o

:: Application State
call php artisan key:generate --force
call php artisan migrate:fresh --seed --force
if exist public\storage rmdir /s /q public\storage
call php artisan storage:link

:: Frontend Build
if not exist node_modules call npm install
call npm run build

:: Performance & Cache
call php artisan config:clear >nul
call php artisan cache:clear >nul
call php artisan view:clear >nul
call php artisan route:clear >nul

:: Launch Engines
start /min "BSMF Queue" cmd /c php artisan queue:work
timeout /t 3 >nul
start "" "http://127.0.0.1:8000"

:: Start Server
php artisan serve
