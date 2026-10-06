@echo off
setlocal
cd /d "%~dp0"

echo ==============================================
echo   SDA - Instalacion de dependencias
echo ==============================================

echo.
echo [1/5] Instalando dependencias PHP...
call composer install
if errorlevel 1 goto :error

echo.
echo [2/5] Instalando dependencias Node...
call npm install
if errorlevel 1 goto :error

echo.
echo [3/5] Preparando archivo .env...
if not exist .env copy .env.example .env >nul

echo.
echo [4/5] Generando APP_KEY...
php artisan key:generate
if errorlevel 1 goto :error

echo.
echo [5/5] Limpiando cache...
php artisan optimize:clear

echo.
echo ==============================================
echo Dependencias listas.
echo Ahora configura MySQL en .env y ejecuta:
echo     php artisan migrate
echo Luego inicia con:
echo     php artisan serve
echo     npm run dev
echo ==============================================
pause
exit /b 0

:error
echo.
echo Ocurrio un error. Verifica que PHP, Composer y Node.js esten instalados y disponibles en PATH.
pause
exit /b 1
