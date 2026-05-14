@echo off
chcp 65001 >nul
color 0A
title 📦 Llenar Todas las Categorías con Productos

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         LLENAR TODAS LAS CATEGORÍAS CON PRODUCTOS          ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 🎯 Este script llenará TODAS las categorías con productos
echo    variados y realistas para tu tienda FiftyOne
echo.
echo 📊 Se crearán aproximadamente 100+ productos en:
echo    - Hoodies, Camisetas, Pantalones, Chaquetas
echo    - Shorts, Sudaderas, Joggers, Jeans
echo    - Buzos, Polos, Camisas, Sweaters
echo    - Abrigos, Chalecos, Bermudas
echo    - Medias, Gorros, Bufandas, Guantes
echo    - Cinturones, Carteras, Lentes, Relojes
echo    - Joyería, Pijamas, y más...
echo.
echo ⚠️  IMPORTANTE:
echo    - No se duplicarán productos existentes
echo    - Cada producto tendrá stock y precio realista
echo    - Se marcarán algunos como destacados
echo.
pause

echo.
echo 🚀 Ejecutando seeder...
echo.

php artisan db:seed --class=LlenarTodoConProductosSeeder

echo.
echo ════════════════════════════════════════════════════════════
echo.
echo ✅ PROCESO COMPLETADO!
echo.
echo 📝 Próximos pasos:
echo    1. Visita tu tienda: http://localhost:8000
echo    2. Revisa el catálogo completo
echo    3. Verifica el panel admin: http://localhost:8000/admin
echo.
echo 💡 TIP: Puedes ejecutar este script las veces que quieras
echo    Solo creará productos que no existan
echo.
pause
