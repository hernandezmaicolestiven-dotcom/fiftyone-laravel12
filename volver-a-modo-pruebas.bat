@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🔄 VOLVER A MODO PRUEBAS - WOMPI
echo ═══════════════════════════════════════════════════════════
echo.
echo ⚠️  ADVERTENCIA:
echo    Actualmente Wompi está en MODO PRODUCCIÓN
echo    Los pagos son REALES y se cobran tarjetas reales
echo.
echo Este script cambiará Wompi a MODO PRUEBAS:
echo    - Los pagos serán simulados
echo    - Solo funcionarán tarjetas de prueba
echo    - No se cobrará dinero real
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause
echo.
echo 🔄 Cambiando a modo pruebas...
echo.

REM Crear archivo temporal con las llaves de prueba
(
echo # Wompi - MODO PRUEBAS
echo WOMPI_PUBLIC_KEY=pub_test_VHRuIqigjYHQESsMAmEujUJ9RIeQaW66
echo WOMPI_PRIVATE_KEY=prv_test_3HlIAQ4EX27ZJHyIDSQNoLhwsFqULckz
echo WOMPI_INTEGRITY_SECRET=test_integrity_ZJn2EkGDLicgsWy2Tfils4pKgAi09P3p
echo WOMPI_EVENTS_SECRET=test_events_P0F8iwIfmKsNAkFlIn0mFGXGIXuTtP3b
echo WOMPI_SANDBOX=true
) > .env.wompi.test

echo ✅ Configuración de pruebas creada
echo.
echo ⚠️  IMPORTANTE:
echo    Debes copiar manualmente estas líneas al archivo .env
echo    y reemplazar las líneas de WOMPI_
echo.
echo O puedes editar .env y cambiar:
echo    WOMPI_SANDBOX=false  →  WOMPI_SANDBOX=true
echo.
echo Luego ejecuta:
echo    php artisan config:clear
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause
