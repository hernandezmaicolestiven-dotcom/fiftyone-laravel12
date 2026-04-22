<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Sincronizar carrito del localStorage a la BD
    public function sync(Request $request)
    {
        $items = $request->validate(['items' => 'required|array']);
        $userId = auth()->id();

        // Limpiar carrito actual del usuario
        CartItem::where('user_id', $userId)->delete();

        // Insertar items nuevos
        foreach ($items['items'] as $item) {
            if (!empty($item['id']) && !empty($item['qty'])) {
                CartItem::updateOrCreate(
                    ['user_id' => $userId, 'product_id' => $item['id'], 'selected_size' => $item['selectedSize'] ?? null],
                    ['quantity' => $item['qty'], 'selected_color' => $item['selectedColor'] ?? null]
                );
            }
        }

        return response()->json(['success' => true]);
    }

    // Obtener carrito guardado en BD
    public function get()
    {
        $items = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->map(fn($i) => [
                'id'            => $i->product_id,
                'name'          => $i->product->name,
                'price'         => (float) $i->product->price,
                'qty'           => $i->quantity,
                'selectedSize'  => $i->selected_size,
                'selectedColor' => $i->selected_color,
                'img'           => $i->product->image
                    ? (str_starts_with($i->product->image, 'http') ? $i->product->image : \Storage::url($i->product->image))
                    : null,
            ]);

        return response()->json($items);
    }
}
