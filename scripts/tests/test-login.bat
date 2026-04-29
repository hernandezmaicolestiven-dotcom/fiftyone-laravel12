@echo off
echo ========================================
echo   PRUEBA DE CREDENCIALES - FIFTYONE
echo ========================================
echo.
echo Probando credenciales de Admin...
php artisan tinker --execute="echo 'Admin: '; if (Auth::attempt(['email' => 'admin@fiftyone.com', 'password' => 'Admin123!'])) { echo 'OK'; } else { echo 'FALLO'; } echo PHP_EOL;"
echo.
echo Probando credenciales de Cliente...
php artisan tinker --execute="echo 'Cliente: '; if (Auth::attempt(['email' => 'cliente@test.com', 'password' => 'Cliente123!'])) { echo 'OK'; } else { echo 'FALLO'; } echo PHP_EOL;"
echo.
echo ========================================
pause
