@echo off
echo ========================================
echo PROBANDO ENDPOINT WOMPI CON CURL
echo ========================================
echo.

REM Primero crear una orden
echo Creando orden de prueba...
curl -X POST http://localhost:8000/orders ^
  -H "Content-Type: application/json" ^
  -H "X-CSRF-TOKEN: test" ^
  -d "{\"customer_name\":\"Test\",\"shipping_address\":\"Test 123\",\"city\":\"Bogota\",\"items\":[{\"product_id\":1,\"quantity\":1}],\"payment_method\":\"wompi\"}"

echo.
echo.
echo Presiona cualquier tecla para probar el endpoint de Wompi...
pause > nul

REM Probar el endpoint de Wompi (reemplaza ORDER_ID con el ID de la orden creada)
echo.
echo Probando endpoint de Wompi...
curl -X POST http://localhost:8000/api/wompi/create-transaction ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"order_id\":1}"

echo.
echo.
echo ========================================
echo PRUEBA COMPLETADA
echo ========================================
pause
