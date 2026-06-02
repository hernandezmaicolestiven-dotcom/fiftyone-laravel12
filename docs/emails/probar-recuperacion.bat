@echo off
chcp 65001 >nul
cls

echo.
echo ═══════════════════════════════════════════════════════════
echo   🔐 PROBAR SISTEMA DE RECUPERACIÓN DE CONTRASEÑA
echo ═══════════════════════════════════════════════════════════
echo.
echo Este script te ayudará a probar el sistema de recuperación
echo de contraseña de FiftyOne.
echo.
echo ═══════════════════════════════════════════════════════════
echo.

:menu
echo.
echo Selecciona una opción:
echo.
echo   1. Verificar sistema
echo   2. Enviar email de prueba
echo   3. Limpiar tokens antiguos
echo   4. Ver logs
echo   5. Abrir formulario en navegador
echo   6. Salir
echo.
set /p opcion="Opción: "

if "%opcion%"=="1" goto verificar
if "%opcion%"=="2" goto enviar
if "%opcion%"=="3" goto limpiar
if "%opcion%"=="4" goto logs
if "%opcion%"=="5" goto navegador
if "%opcion%"=="6" goto fin

echo.
echo ❌ Opción inválida
goto menu

:verificar
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Verificando sistema...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
php scripts/test-password-reset.php
pause
goto menu

:enviar
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Enviando email de prueba...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
php scripts/send-test-password-reset.php
pause
goto menu

:limpiar
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Limpiando tokens antiguos...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
php artisan tinker --execute="DB::table('password_reset_tokens')->truncate(); echo 'Tokens eliminados';"
echo.
echo ✅ Tokens eliminados correctamente
pause
goto menu

:logs
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Últimas 20 líneas del log...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
powershell -Command "Get-Content storage/logs/laravel.log -Tail 20"
pause
goto menu

:navegador
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Abriendo formulario en navegador...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
start http://localhost:8000/recuperar-contrasena
echo.
echo ✅ Navegador abierto
pause
goto menu

:fin
echo.
echo ═══════════════════════════════════════════════════════════
echo   👋 ¡Hasta luego!
echo ═══════════════════════════════════════════════════════════
echo.
exit
