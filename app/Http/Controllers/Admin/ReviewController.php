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
        return back()->with('success', 'Reseña eliminada.');
    }
}
