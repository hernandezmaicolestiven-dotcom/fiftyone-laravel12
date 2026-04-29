@echo off
echo ========================================
echo   Extraer Enlace de Recuperacion
echo ========================================
echo.
powershell -Command "$content = Get-Content storage/logs/laravel.log -Raw; if ($content -match 'href=\"(http://localhost:8000/restablecer-contrasena/[^\"]+)\"') { Write-Host 'ENLACE ENCONTRADO:' -ForegroundColor Green; Write-Host ''; Write-Host $matches[1] -ForegroundColor Yellow; Write-Host ''; Write-Host 'Copia y pega este enlace en tu navegador' -ForegroundColor Cyan } else { Write-Host 'No se encontro ningun enlace de recuperacion' -ForegroundColor Red; Write-Host 'Asegurate de haber solicitado la recuperacion primero' -ForegroundColor Yellow }"
echo.
pause
