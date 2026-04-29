<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $productId = request('product_id');
        $query = Review::with(['product', 'user'])->latest();
        if ($productId) {
            $query->where('product_id', $productId);
        }
        $reviews = $query->paginate(20);
        return view('admin.reviews.index', compact('reviews', 'products', 'productId'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Reseña movida a la papelera.');
    }

    /**
     * Mostrar reseñas en papelera
     */
    public function trashed()
    {
        $reviews = Review::onlyTrashed()->with(['product', 'user'])->latest('deleted_at')->paginate(20);
        return view('admin.reviews.trashed', compact('reviews'));
    }

    /**
     * Restaurar reseña de la papelera
     */
    public function restore($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();

        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Reseña restaurada correctamente.');
    }

    /**
     * Eliminar permanentemente
     */
    public function forceDelete($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();

        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Reseña eliminada permanentemente.');
    }
}
