<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.store-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'store_name', 'store_phone', 'store_email', 'store_address',
            'store_instagram', 'store_whatsapp', 'shipping_price', 'free_shipping_min',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Limpiar caché de configuración
        cache()->forget('store_settings');

        return back()->with('success', 'Configuracion de la tienda actualizada.');
    }
}
