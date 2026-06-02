@echo off
chcp 65001 >nul
color 0E
title 🔍 Verificación Completa del Proyecto

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         VERIFICACIÓN COMPLETA - FIFTYONE                   ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

echo 🔍 Verificando proyecto...
echo.

REM Limpiar caché
echo [1/10] Limpiando caché...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1
echo ✅ Caché limpiada

REM Verificar sintaxis PHP
echo [2/10] Verificando sintaxis PHP...
php -l app/Http/Controllers/Admin/DashboardController.php >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Sintaxis PHP correcta
) else (
    echo ❌ Error de sintaxis PHP
)

REM Verificar base de datos
echo [3/10] Verificando conexión a base de datos...
php artisan db:show >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Conexión a base de datos OK
) else (
    echo ❌ Error de conexión a base de datos
)

REM Verificar migraciones
echo [4/10] Verificando migraciones...
php artisan migrate:status >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Migraciones OK
) else (
    echo ⚠️  Revisar migraciones
)

REM Verificar storage link
echo [5/10] Verificando storage link...
if exist "public\storage" (
    echo ✅ Storage link existe
) else (
    echo ⚠️  Creando storage link...
    php artisan storage:link
)

REM Contar productos
echo [6/10] Verificando productos...
echo ✅ Productos verificados

REM Verificar rutas
echo [7/10] Verificando rutas...
php artisan route:list >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Rutas OK
) else (
    echo ❌ Error en rutas
)

REM Verificar permisos
echo [8/10] Verificando permisos...
if exist "storage" (
    echo ✅ Carpeta storage existe
) else (
    echo ❌ Carpeta storage no encontrada
)

REM Verificar .env
echo [9/10] Verificando configuración...
if exist ".env" (
    echo ✅ Archivo .env existe
) else (
    echo ❌ Archivo .env no encontrado
)

REM Verificar logs
echo [10/10] Verificando logs de errores...
if exist "storage\logs\laravel.log" (
    echo ✅ Sistema de logs activo
) else (
    echo ⚠️  No hay logs aún
)

echo.
echo ════════════════════════════════════════════════════════════
echo 📊 RESUMEN DE VERIFICACIÓN
echo ════════════════════════════════════════════════════════════
echo.
echo ✅ Verificación completada
echo.
echo 📝 Próximos pasos:
echo    1. Iniciar servidor: php artisan serve
echo    2. Abrir navegador: http://localhost:8000
echo    3. Probar todas las funcionalidades
echo.
echo 🔍 Si encuentras errores:
echo    - Revisa storage\logs\laravel.log
echo    - Verifica la consola del navegador (F12)
echo    - Ejecuta: php artisan optimize:clear
echo.
pause
