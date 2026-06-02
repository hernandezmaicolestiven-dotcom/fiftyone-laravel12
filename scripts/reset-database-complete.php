<?php

/**
 * Script completo para resetear la base de datos
 * Incluye: usuarios, categorías, productos, órdenes, cupones, etc.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  🔄 RESETEO COMPLETO DE BASE DE DATOS                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "⚠️  ADVERTENCIA: Esto eliminará TODOS los datos actuales\n";
echo "\n";
echo "Presiona ENTER para continuar o CTRL+C para cancelar...\n";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

echo "\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "\n";

// 1. Limpiar caché
echo "🧹 Limpiando caché...\n";
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
echo "   ✅ Caché limpiado\n\n";

// 2. Eliminar base de datos
echo "🗑️  Eliminando base de datos actual...\n";
Artisan::call('migrate:fresh', ['--force' => true]);
echo "   ✅ Base de datos eliminada\n\n";

// 3. Ejecutar migraciones
echo "📦 Ejecutando migraciones...\n";
Artisan::call('migrate', ['--force' => true]);
echo "   ✅ Migraciones ejecutadas\n\n";

// 4. Crear usuarios
echo "👥 Creando usuarios...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\AdminUserSeeder', '--force' => true]);
echo "   ✅ Admin creado\n";

// Crear usuarios adicionales manualmente
$users = [
    [
        'name' => 'Maicol Hernandez',
        'email' => 'hernandezmaicolestiven@gmail.com',
        'password' => bcrypt('12345678'),
        'role' => 'customer',
        'phone' => '3009876543',
    ],
    [
        'name' => 'Cliente de Prueba',
        'email' => 'cliente@test.com',
        'password' => bcrypt('cliente123'),
        'role' => 'customer',
        'phone' => '3005555555',
    ],
    [
        'name' => 'Colaborador FiftyOne',
        'email' => 'colaborador@fiftyone.com',
        'password' => bcrypt('colab123'),
        'role' => 'colaborador',
        'phone' => '3007777777',
    ],
];

foreach ($users as $userData) {
    \App\Models\User::create($userData);
    echo "   ✅ Usuario creado: {$userData['email']}\n";
}
echo "\n";

// 5. Crear categorías
echo "📁 Creando categorías...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\CategorySeeder', '--force' => true]);
echo "   ✅ Categorías creadas\n\n";

// 6. Crear productos con imágenes de calidad
echo "🛍️  Creando productos con imágenes...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\QualityProductSeeder', '--force' => true]);
echo "   ✅ Productos creados\n\n";

// 7. Crear configuración de facturación
echo "🧾 Configurando sistema de facturación...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\InvoiceSettingsSeeder', '--force' => true]);
echo "   ✅ Facturación configurada\n\n";

// 8. Crear datos de prueba (órdenes, cupones, reseñas)
echo "📊 Creando datos de prueba...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TestDataSeeder', '--force' => true]);
echo "   ✅ Datos de prueba creados\n\n";

// 9. Llenar datos para reportes
echo "📈 Llenando datos para reportes...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\FillReportsDataSeeder', '--force' => true]);
echo "   ✅ Datos de reportes creados\n\n";

// 10. Verificar datos creados
echo "🔍 Verificando datos creados...\n";
$stats = [
    'Usuarios' => DB::table('users')->count(),
    'Categorías' => DB::table('categories')->count(),
    'Productos' => DB::table('products')->count(),
    'Órdenes' => DB::table('orders')->count(),
    'Cupones' => DB::table('coupons')->count(),
    'Reseñas' => DB::table('reviews')->count(),
];

foreach ($stats as $table => $count) {
    echo "   ✅ {$table}: {$count}\n";
}
echo "\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ BASE DE DATOS RESETEADA EXITOSAMENTE                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "┌────────────────────────────────────────────────────────────────┐\n";
echo "│ 🔑 CREDENCIALES DE ACCESO                                      │\n";
echo "├────────────────────────────────────────────────────────────────┤\n";
echo "│                                                                │\n";
echo "│ 👨‍💼 ADMIN                                                      │\n";
echo "│    Email:    admin@fiftyone.com                               │\n";
echo "│    Password: admin123                                         │\n";
echo "│    URL:      http://localhost:8000/admin/login                │\n";
echo "│                                                                │\n";
echo "│ 👤 MAICOL HERNANDEZ                                            │\n";
echo "│    Email:    hernandezmaicolestiven@gmail.com                 │\n";
echo "│    Password: 12345678                                         │\n";
echo "│    URL:      http://localhost:8000/login                      │\n";
echo "│                                                                │\n";
echo "│ 👤 CLIENTE DE PRUEBA                                           │\n";
echo "│    Email:    cliente@test.com                                 │\n";
echo "│    Password: cliente123                                       │\n";
echo "│    URL:      http://localhost:8000/login                      │\n";
echo "│                                                                │\n";
echo "│ 🤝 COLABORADOR                                                 │\n";
echo "│    Email:    colaborador@fiftyone.com                         │\n";
echo "│    Password: colab123                                         │\n";
echo "│    URL:      http://localhost:8000/admin/login                │\n";
echo "│                                                                │\n";
echo "└────────────────────────────────────────────────────────────────┘\n";
echo "\n";

echo "📄 Las credenciales están guardadas en: CREDENCIALES_ACCESO.txt\n";
echo "\n";

echo "🚀 Próximos pasos:\n";
echo "   1. Inicia el servidor: php artisan serve\n";
echo "   2. Ve a: http://localhost:8000\n";
echo "   3. Inicia sesión con cualquiera de las cuentas\n";
echo "\n";

echo "════════════════════════════════════════════════════════════════\n";
echo "\n";
