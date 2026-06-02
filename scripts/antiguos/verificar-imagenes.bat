@echo off
chcp 65001 >nul
echo.
echo ═══════════════════════════════════════════════════════════
echo   🖼️  VERIFICACIÓN DE IMÁGENES - FIFTYONE
echo ═══════════════════════════════════════════════════════════
echo.
echo 📋 Verificando productos con imágenes...
echo.

php artisan tinker --execute="$products = App\Models\Product::all(); echo '✅ Total de productos: ' . $products->count() . PHP_EOL; echo '🖼️  Productos con imágenes: ' . $products->where('image', '!=', null)->count() . PHP_EOL; echo PHP_EOL . '📸 Primeras 5 imágenes:' . PHP_EOL; $products->take(5)->each(function($p) { echo '  • ' . $p->name . PHP_EOL . '    ' . $p->image . PHP_EOL; });"

echo.
echo ═══════════════════════════════════════════════════════════
echo   ✅ VERIFICACIÓN COMPLETA
echo ═══════════════════════════════════════════════════════════
echo.
echo 🌐 Ahora abre tu navegador en: http://localhost:8000
echo.
echo 💡 Si las imágenes no se ven:
echo    1. Presiona Ctrl + Shift + R para forzar recarga
echo    2. Limpia el caché del navegador
echo    3. Verifica tu conexión a internet (las imágenes vienen de Unsplash)
echo.
pause
