<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:1',
            'uses_left'  => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'       => strtoupper(Str::random(8)),
            'type'       => $request->type,
            'value'      => $request->value,
            'uses_left'  => $request->uses_left,
            'expires_at' => $request->expires_at,
            'active'     => true,
        ]);

        return back()->with('success', 'Cupón generado correctamente.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['active' => !$coupon->active]);
        return back()->with('success', 'Estado del cupón actualizado.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupón movido a la papelera.');
    }

    /**
     * Mostrar cupones en papelera
     */
    public function trashed()
    {
        $coupons = Coupon::onlyTrashed()->latest('deleted_at')->get();
        return view('admin.coupons.trashed', compact('coupons'));
    }

    /**
     * Restaurar cupón de la papelera
     */
    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()->route('admin.coupons.trashed')
            ->with('success', 'Cupón restaurado correctamente.');
    }

    /**
     * Eliminar permanentemente
     */
    public function forceDelete($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()->route('admin.coupons.trashed')
            ->with('success', 'Cupón eliminado permanentemente.');
    }
}
