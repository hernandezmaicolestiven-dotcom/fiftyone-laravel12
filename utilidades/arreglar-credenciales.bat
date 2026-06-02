@echo off
chcp 65001 >nul
cls

echo.
echo ╔════════════════════════════════════════════════════════════════╗
echo ║  🔧 ARREGLAR CREDENCIALES - FIFTYONE                          ║
echo ╚════════════════════════════════════════════════════════════════╝
echo.
echo Este script arreglará todas las credenciales de usuarios
echo y las restaurará a valores conocidos.
echo.
echo Presiona cualquier tecla para continuar...
pause >nul

cls
echo.
echo ⏳ Arreglando credenciales...
echo.

php scripts/fix-credentials.php

echo.
echo ═══════════════════════════════════════════════════════════════
echo.
echo 📄 Las credenciales están guardadas en: CREDENCIALES_ACCESO.txt
echo.
echo Presiona cualquier tecla para salir...
pause >nul
