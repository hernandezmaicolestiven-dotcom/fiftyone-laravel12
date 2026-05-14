@echo off
chcp 65001 >nul
color 0B
title ✅ Verificar Proyecto FiftyOne

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         VERIFICACIÓN COMPLETA DEL PROYECTO                 ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

echo 🔍 Verificando componentes del proyecto...
echo.

REM Verificar PHP
echo [1/8] Verificando PHP...
php --version >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ PHP instalado
) else (
    echo ❌ PHP no encontrado
)

REM Verificar Composer
echo [2/8] Verificando Composer...
composer --version >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Composer instalado
) else (
    echo ❌ Composer no encontrado
)

REM Verificar archivo .env
echo [3/8] Verificando configuración...
if exist ".env" (
    echo ✅ Archivo .env existe
) else (
    echo ❌ Archivo .env no encontrado
)

REM Verificar conexión a base de datos
echo [4/8] Verificando base de datos...
php artisan db:show >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Conexión a base de datos OK
) else (
    echo ⚠️  No se pudo conectar a la base de datos
)

REM Contar productos
echo [5/8] Contando productos...
for /f %%i in ('php artisan tinker --execute="echo Product::count();"') do set PRODUCTOS=%%i
echo ✅ Productos en base de datos: %PRODUCTOS%

REM Contar categorías
echo [6/8] Contando categorías...
for /f %%i in ('php artisan tinker --execute="echo Category::count();"') do set CATEGORIAS=%%i
echo ✅ Categorías en base de datos: %CATEGORIAS%

REM Verificar storage link
echo [7/8] Verificando storage...
if exist "public\storage" (
    echo ✅ Storage link existe
) else (
    echo ⚠️  Storage link no encontrado - ejecutando...
    php artisan storage:link
)

REM Verificar permisos
echo [8/8] Verificando permisos...
if exist "storage" (
    echo ✅ Carpeta storage existe
) else (
    echo ❌ Carpeta storage no encontrada
)

echo.
echo ════════════════════════════════════════════════════════════
echo 📊 RESUMEN DEL PROYECTO
echo ════════════════════════════════════════════════════════════
echo.
echo 📦 Productos: %PRODUCTOS%
echo 📁 Categorías: %CATEGORIAS%
echo.
echo 🌐 URLs de acceso:
echo    Tienda:  http://localhost:8000
echo    Admin:   http://localhost:8000/admin
echo.
echo 🔐 Credenciales:
echo    Admin:   admin@fiftyone.com / admin2026
echo    Cliente: cliente@test.com / cliente2026
echo.
echo ════════════════════════════════════════════════════════════
echo.

if %PRODUCTOS% GTR 50 (
    echo ✅ PROYECTO COMPLETO Y LISTO
    echo.
    echo 🚀 Para iniciar el servidor:
    echo    php artisan serve
    echo.
    echo 📖 Documentación:
    echo    - PROYECTO_LISTO.md
    echo    - CHECKLIST_PROYECTO_COMPLETO.md
    echo    - CREDENCIALES_ACCESO.txt
    echo.
) else (
    echo ⚠️  FALTAN PRODUCTOS
    echo.
    echo 📝 Para llenar productos:
    echo    php artisan db:seed --class=LlenarTodoConProductosSeeder
    echo.
    echo O ejecuta: llenar-productos.bat
    echo.
)

pause
