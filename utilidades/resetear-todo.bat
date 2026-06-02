@echo off
chcp 65001 >nul
cls

echo.
echo ╔════════════════════════════════════════════════════════════════╗
echo ║  🔄 RESETEAR BASE DE DATOS COMPLETA - FIFTYONE                ║
echo ╚════════════════════════════════════════════════════════════════╝
echo.
echo ⚠️  ADVERTENCIA: Esto eliminará TODOS los datos actuales
echo.
echo Este script hará lo siguiente:
echo   • Eliminar toda la base de datos
echo   • Ejecutar todas las migraciones
echo   • Crear usuarios (admin, Maicol, cliente, colaborador)
echo   • Crear categorías
echo   • Crear productos con imágenes
echo   • Crear órdenes de prueba
echo   • Crear cupones
echo   • Crear reseñas
echo   • Configurar facturación
echo   • Llenar datos para reportes
echo.
echo Presiona cualquier tecla para continuar o CTRL+C para cancelar...
pause >nul

cls
echo.
echo ⏳ Reseteando base de datos...
echo.

php scripts/reset-database-complete.php

echo.
echo Presiona cualquier tecla para salir...
pause >nul
