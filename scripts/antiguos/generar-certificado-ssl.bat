@echo off
chcp 65001 >nul
color 0E
title 🔐 Generar Certificado SSL Local

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         GENERAR CERTIFICADO SSL AUTOFIRMADO                ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

REM Verificar si OpenSSL está instalado
where openssl >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ❌ OpenSSL no está instalado
    echo.
    echo 📥 Descarga OpenSSL desde:
    echo    👉 https://slproweb.com/products/Win32OpenSSL.html
    echo.
    echo    Instala "Win64 OpenSSL v3.x.x Light"
    echo.
    pause
    exit /b 1
)

echo ✅ OpenSSL encontrado
echo.
echo 📝 Generando certificado SSL para fiftyone.local...
echo.

REM Crear directorio para certificados
if not exist "ssl" mkdir ssl
cd ssl

REM Generar clave privada
echo 🔑 Generando clave privada...
openssl genrsa -out fiftyone.key 2048

REM Generar certificado
echo 📜 Generando certificado...
openssl req -new -x509 -key fiftyone.key -out fiftyone.crt -days 365 -subj "/C=CO/ST=Bogota/L=Bogota/O=FiftyOne/CN=fiftyone.local"

echo.
echo ✅ Certificado generado exitosamente!
echo.
echo 📁 Archivos creados en la carpeta 'ssl':
echo    - fiftyone.key (clave privada)
echo    - fiftyone.crt (certificado)
echo.
echo ════════════════════════════════════════════════════════════
echo 📋 SIGUIENTES PASOS:
echo ════════════════════════════════════════════════════════════
echo.
echo 1️⃣  Instalar el certificado en Windows:
echo    - Doble clic en ssl\fiftyone.crt
echo    - Clic en "Instalar certificado"
echo    - Seleccionar "Equipo local"
echo    - Siguiente ^> Siguiente ^> Finalizar
echo.
echo 2️⃣  Agregar dominio al archivo hosts:
echo    - Abrir como ADMINISTRADOR: notepad C:\Windows\System32\drivers\etc\hosts
echo    - Agregar al final: 127.0.0.1 fiftyone.local
echo    - Guardar y cerrar
echo.
echo 3️⃣  Actualizar .env:
echo    APP_URL=https://fiftyone.local:8000
echo.
echo 4️⃣  Iniciar servidor Laravel con SSL:
echo    php -S fiftyone.local:8000 -t public
echo.
echo ⚠️  NOTA: El navegador mostrará advertencia de seguridad
echo    (es normal con certificados autofirmados)
echo    Haz clic en "Avanzado" ^> "Continuar al sitio"
echo.
echo ════════════════════════════════════════════════════════════
echo.
pause
cd ..
