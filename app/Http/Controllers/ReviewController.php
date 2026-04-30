<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ]);

        Review::updateOrCreate(
            ['product_id' => $request->product_id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return response()->json(['success' => true]);
    }

    public function index(Request $request)
    {
        $reviews = Review::with('user')
            ->where('product_id', $request->product_id)
            ->approved() // Solo reseñas aprobadas
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id'      => $r->id,
                'user'    => $r->user->name,
                'rating'  => $r->rating,
                'comment' => $r->comment,
                'date'    => $r->created_at->format('d/m/Y'),
            ]);

        return response()->json($reviews);
    }
}
