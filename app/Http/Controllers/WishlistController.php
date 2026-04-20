<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['wishlisted' => false]);
        }

        Wishlist::create(['user_id' => auth()->id(), 'product_id' => $request->product_id]);
        return response()->json(['wishlisted' => true]);
    }

    public function index()
    {
        $items = Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return response()->json($items->map(fn($w) => [
            'id'    => $w->product->id,
            'name'  => $w->product->name,
            'price' => $w->product->price,
            'img'   => $w->product->image ? (str_starts_with($w->product->image,'http') ? $w->product->image : \Storage::url($w->product->image)) : null,
        ]));
    }
}
