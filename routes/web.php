<?php

use App\Http\Controllers\Admin\StoreSettingsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\GeneratorController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminManagerController;
use App\Http\Controllers\Admin\ColaboradorController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\OrderController as PublicOrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    // Cachear productos por 5 minutos para mejorar rendimiento
    $products = cache()->remember('home_products', 300, function () {
        return Product::with(['category', 'reviews'])
            ->latest()
            ->take(50) // Limitar a 50 productos más recientes
            ->get();
    });
    
    $authUser = auth()->user();
    $authData = [
        'loggedIn' => $authUser && $authUser->role !== 'admin',
        'name'     => ($authUser && $authUser->role !== 'admin') ? $authUser->name : null,
        'id'       => ($authUser && $authUser->role !== 'admin') ? $authUser->id : null,
    ];
    
    // Wishlist del usuario (solo si está autenticado)
    $wishlistIds = [];
    if ($authUser && $authUser->role !== 'admin') {
        $wishlistIds = \App\Models\Wishlist::where('user_id', $authUser->id)
            ->pluck('product_id')
            ->toArray();
    }
    
    // Reseñas para mostrar en home (últimas 6, cacheadas)
    $reviews = cache()->remember('home_reviews', 300, function () {
        return \App\Models\Review::with(['user:id,name', 'product:id,name'])
            ->latest()
            ->take(6)
            ->get()
            ->map(fn($r) => [
                'name'    => $r->user->name,
                'product' => $r->product->name ?? '',
                'rating'  => $r->rating,
                'comment' => $r->comment,
                'date'    => $r->created_at->format('d/m/Y'),
            ]);
    });
    
    return view('welcome', compact('products', 'authData', 'reviews', 'wishlistIds'));
});

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('login', [AuthController::class, 'login'])->name('login.post')->middleware(['guest', 'throttle:5,1']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Recuperación de contraseña
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request')->middleware('guest');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email')->middleware('guest');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset')->middleware('guest');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

    // Protected admin routes — solo role=admin
    Route::middleware(['auth', 'admin.only'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('products/export/csv', [AdminProductController::class, 'exportCsv'])->name('products.export.csv');
        Route::get('products/export/excel', [AdminProductController::class, 'exportExcel'])->name('products.export.excel');
        Route::post('products/import/csv', [AdminProductController::class, 'importCsv'])->name('products.import.csv');
        Route::get('products/trashed', [AdminProductController::class, 'trashed'])->name('products.trashed');
        Route::patch('products/{id}/restore', [AdminProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::resource('products', AdminProductController::class);
        Route::get('categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
        Route::patch('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
        Route::resource('categories', CategoryController::class);
        Route::post('users/import/csv', [UserController::class, 'importCsv'])->name('users.import.csv');
        Route::get('users/export/csv', [UserController::class, 'exportCsv'])->name('users.export.csv');
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('users', UserController::class);
        Route::get('orders/export/csv', [OrderController::class, 'exportCsv'])->name('orders.export.csv');
        Route::get('orders/export/pdf', [OrderController::class, 'exportPdf'])->name('orders.export.pdf');
        Route::get('orders/trashed', [OrderController::class, 'trashed'])->name('orders.trashed');
        Route::patch('orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
        Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        // Reportes
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('reports/top-products', [ReportController::class, 'topProducts'])->name('reports.top-products');

        // Analytics avanzado + IA
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');

        // Chat interno
        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
        Route::get('messages/poll', [MessageController::class, 'poll'])->name('messages.poll');

        // Configuracion de la tienda
        Route::get('store-settings', [StoreSettingsController::class, 'index'])->name('store-settings.index');
        Route::put('store-settings', [StoreSettingsController::class, 'update'])->name('store-settings.update');

        // Configuracion admin existente
        Route::get('settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::delete('settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');

        // Reseñas
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('reviews/trashed', [AdminReviewController::class, 'trashed'])->name('reviews.trashed');
        Route::patch('reviews/{id}/restore', [AdminReviewController::class, 'restore'])->name('reviews.restore');
        Route::delete('reviews/{id}/force-delete', [AdminReviewController::class, 'forceDelete'])->name('reviews.force-delete');

        // Cupones
        Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::get('coupons/trashed', [CouponController::class, 'trashed'])->name('coupons.trashed');
        Route::patch('coupons/{id}/restore', [CouponController::class, 'restore'])->name('coupons.restore');
        Route::delete('coupons/{id}/force-delete', [CouponController::class, 'forceDelete'])->name('coupons.force-delete');

        // Generadores
        Route::get('generators/invoice/{order}', [GeneratorController::class, 'invoice'])->name('generators.invoice');
        Route::get('generators/label/{order}', [GeneratorController::class, 'label'])->name('generators.label');

        // Colaboradores — solo admin y superadmin
        Route::get('colaboradores', [ColaboradorController::class, 'index'])->name('colaboradores.index');
        Route::post('colaboradores', [ColaboradorController::class, 'store'])->name('colaboradores.store');
        Route::delete('colaboradores/{user}', [ColaboradorController::class, 'destroy'])->name('colaboradores.destroy');

        // Administradores — solo superadmin
        Route::get('admins', [AdminManagerController::class, 'index'])->name('admins.index');
        Route::post('admins', [AdminManagerController::class, 'store'])->name('admins.store');
        Route::delete('admins/{user}', [AdminManagerController::class, 'destroy'])->name('admins.destroy');
        Route::patch('admins/{user}/reset', [AdminManagerController::class, 'resetPassword'])->name('admins.reset');

        // Perfil
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Public order endpoint — requiere sesión de cliente
Route::post('/orders', [PublicOrderController::class, 'store'])->name('orders.store')->middleware('auth');

// Facturas (requiere auth)
Route::middleware('auth')->group(function () {
    Route::get('/factura/{order}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/factura/{order}/descargar', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/api/factura/{order}', [\App\Http\Controllers\InvoiceController::class, 'getInvoiceData'])->name('invoice.data');
});

// Validar cupón (público)
Route::get('/coupon/validate', function (\Illuminate\Http\Request $request) {
    $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))->first();
    if (!$coupon || !$coupon->isValid()) {
        return response()->json(['valid' => false, 'message' => 'Cupón inválido o expirado']);
    }
    $discount = $coupon->discount((float) $request->total);
    return response()->json(['valid' => true, 'discount' => $discount]);
});

// Reseñas públicas (requiere auth cliente)
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

// Customer auth
Route::middleware('guest')->group(function () {
    Route::get('/registro', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/registro', [CustomerAuthController::class, 'register'])->name('customer.register.post');
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.post');
    
    // Recuperación de contraseña
    Route::get('/recuperar-contrasena', [CustomerAuthController::class, 'showForgotPassword'])->name('customer.password.request');
    Route::post('/recuperar-contrasena', [CustomerAuthController::class, 'sendResetLink'])->name('customer.password.email');
    Route::get('/restablecer-contrasena/{token}', [CustomerAuthController::class, 'showResetPassword'])->name('customer.password.reset');
    Route::post('/restablecer-contrasena', [CustomerAuthController::class, 'resetPassword'])->name('customer.password.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/mi-cuenta', [CustomerAuthController::class, 'account'])->name('customer.account');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::put('/mi-cuenta/perfil', [CustomerAuthController::class, 'updateProfile'])->name('customer.profile.update');
    Route::put('/mi-cuenta/password', [CustomerAuthController::class, 'updatePassword'])->name('customer.password.update');
    Route::patch('/mi-cuenta/orders/{order}/cancel', [CustomerAuthController::class, 'cancelOrder'])->name('customer.order.cancel');
});

// Public catalog routes
// Public catalog routes
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');
Route::get('/productos', [ProductController::class, 'index'])->name('catalogo.productos');

// Páginas estáticas
Route::view('/sobre-nosotros', 'pages.sobre-nosotros')->name('sobre-nosotros');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/trabaja-con-nosotros', 'pages.trabaja')->name('trabaja');
Route::view('/contacto', 'pages.contacto')->name('contacto');
Route::view('/envios', 'pages.envios')->name('envios');
Route::view('/devoluciones', 'pages.devoluciones')->name('devoluciones');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/privacidad', 'pages.privacidad')->name('privacidad');
Route::view('/terminos', 'pages.terminos')->name('terminos');
