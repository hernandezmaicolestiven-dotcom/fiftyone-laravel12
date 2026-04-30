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
        $status = request('status'); // pending, approved, rejected
        
        $query = Review::with(['product', 'user', 'approver'])->latest();
        
        if ($productId) {
            $query->where('product_id', $productId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $reviews = $query->paginate(20)->withQueryString();
        
        // Estadísticas
        $stats = [
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'total' => Review::count(),
        ];
        
        return view('admin.reviews.index', compact('reviews', 'products', 'productId', 'status', 'stats'));
    }

    /**
     * Aprobar reseña
     */
    public function approve(Review $review)
    {
        $review->approve(auth()->id());
        
        return back()->with('success', 'Reseña aprobada correctamente.');
    }

    /**
     * Rechazar reseña
     */
    public function reject(Review $review)
    {
        $review->reject();
        
        return back()->with('success', 'Reseña rechazada.');
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
