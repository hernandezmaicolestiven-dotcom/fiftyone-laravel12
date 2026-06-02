@echo off
chcp 65001 >nul
color 0B
title 🚀 Configurar NGROK para Wompi Real

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         CONFIGURAR NGROK - WOMPI REAL                      ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📋 PASOS PARA ACTIVAR WOMPI CON NGROK:
echo.
echo 1️⃣  Descargar ngrok:
echo    👉 https://ngrok.com/download
echo.
echo 2️⃣  Crear cuenta gratis:
echo    👉 https://dashboard.ngrok.com/signup
echo.
echo 3️⃣  Copiar tu token de autenticación
echo.
echo 4️⃣  Ejecutar en CMD (donde está ngrok.exe):
echo    ngrok config add-authtoken TU_TOKEN_AQUI
echo.
echo 5️⃣  Iniciar Laravel:
echo    php artisan serve
echo.
echo 6️⃣  En OTRA terminal, iniciar ngrok:
echo    ngrok http 8000
echo.
echo 7️⃣  Copiar la URL HTTPS que te da ngrok
echo    Ejemplo: https://abc123.ngrok.io
echo.
echo 8️⃣  Actualizar .env con esa URL:
echo    APP_URL=https://abc123.ngrok.io
echo.
echo 9️⃣  Limpiar caché:
echo    php artisan config:clear
echo.
echo 🔟 Configurar webhook en Wompi:
echo    https://abc123.ngrok.io/api/wompi/webhook
echo.
echo ✅ LISTO! Wompi funcionará con pagos reales
echo.
echo ⚠️  IMPORTANTE:
echo    - La URL de ngrok cambia cada vez que lo reinicias
echo    - Para URL permanente: ngrok.com/pricing ($8/mes)
echo    - Los pagos son REALES y cobran dinero real
echo.
echo 📖 Guía completa: COMO_ACTIVAR_WOMPI_REAL.md
echo.
pause
