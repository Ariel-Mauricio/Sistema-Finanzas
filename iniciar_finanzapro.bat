@echo off
chcp 65001 >nul
title FinanzaPro - Sistema de Gestión Financiera
color 0A

echo.
echo ╔══════════════════════════════════════════════════════════════════╗
echo ║                                                                  ║
echo ║       ███████╗██╗███╗   ██╗ █████╗ ███╗   ██╗███████╗ █████╗     ║
echo ║       ██╔════╝██║████╗  ██║██╔══██╗████╗  ██║╚══███╔╝██╔══██╗    ║
echo ║       █████╗  ██║██╔██╗ ██║███████║██╔██╗ ██║  ███╔╝ ███████║    ║
echo ║       ██╔══╝  ██║██║╚██╗██║██╔══██║██║╚██╗██║ ███╔╝  ██╔══██║    ║
echo ║       ██║     ██║██║ ╚████║██║  ██║██║ ╚████║███████╗██║  ██║    ║
echo ║       ╚═╝     ╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚═╝  ╚═══╝╚══════╝╚═╝  ╚═╝    ║
echo ║                         PRO v2.0                                 ║
echo ║                                                                  ║
echo ║          Sistema de Gestión Financiera Profesional              ║
echo ║                                                                  ║
echo ╚══════════════════════════════════════════════════════════════════╝
echo.

echo [INFO] Verificando servicios...
echo.

:: Verificar si Apache está corriendo
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [✓] Apache está ejecutándose
) else (
    echo [!] Apache no está ejecutándose
    echo     Por favor inicie Apache desde XAMPP Control Panel
)

:: Verificar si MySQL está corriendo
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [✓] MySQL está ejecutándose
) else (
    echo [!] MySQL no está ejecutándose
    echo     Por favor inicie MySQL desde XAMPP Control Panel
)

echo.
echo ══════════════════════════════════════════════════════════════════
echo                    INICIANDO SERVIDOR LARAVEL
echo ══════════════════════════════════════════════════════════════════
echo.
echo [INFO] El servidor se iniciará en: http://localhost:8000
echo [INFO] Presione Ctrl+C para detener el servidor
echo.
echo ══════════════════════════════════════════════════════════════════
echo.
echo CREDENCIALES DE ACCESO:
echo.
echo   Administrador:
echo   Email: admin@finanzapro.com
echo   Contraseña: Admin@2026#Secure
echo.
echo   Contador:
echo   Email: contador@finanzapro.com
echo   Contraseña: Contador@2026#
echo.
echo ══════════════════════════════════════════════════════════════════
echo.

cd /d "%~dp0"
php artisan serve --host=0.0.0.0 --port=8000

pause
