<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $coupons = Coupon::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%")
                                   ->orWhere('surname', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                             });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.coupons.index', compact('coupons', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_type' => 'required|in:auto,manual',
            'code' => 'nullable|required_if:code_type,manual|string|max:50|unique:coupons,code',
            'discount_value_percentage' => 'required|numeric|min:1|max:100',
            'is_used' => 'nullable|boolean',
        ], [
            'code.required_if' => 'Pole kod jest wymagane, jeśli wybrano ręczne generowanie.',
            'code.unique' => 'Ten kod już istnieje w systemie.',
            'discount_value_percentage.required' => 'Wartość rabatu jest wymagana.',
            'discount_value_percentage.numeric' => 'Wartość rabatu musi być liczbą.',
            'discount_value_percentage.min' => 'Wartość rabatu musi wynosić co najmniej 1%.',
            'discount_value_percentage.max' => 'Wartość rabatu nie może przekraczać 100%.',
        ]);

        $code = $request->input('code_type') === 'auto'
            ? strtoupper(Str::random(8))
            : strtoupper($request->input('code'));

        if ($request->input('code_type') === 'auto') {
            while (Coupon::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }
        }

        Coupon::create([
            'code' => $code,
            'discount_value' => $request->input('discount_value_percentage') / 100, // Zapisz jako ułamek
            'is_used' => $request->input('is_used', false),
            'user_id' => null, // Na razie nie przypisujemy
            'created_at' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie utworzony.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->coupon_id . ',coupon_id',
            'discount_value_percentage' => 'required|numeric|min:1|max:100',
            'is_used' => 'nullable|boolean',
        ],[
            'code.unique' => 'Ten kod już istnieje w systemie.',
            'discount_value_percentage.required' => 'Wartość rabatu jest wymagana.',
            'discount_value_percentage.numeric' => 'Wartość rabatu musi być liczbą.',
            'discount_value_percentage.min' => 'Wartość rabatu musi wynosić co najmniej 1%.',
            'discount_value_percentage.max' => 'Wartość rabatu nie może przekraczać 100%.',
        ]);

        $coupon->update([
            'code' => strtoupper($request->input('code')),
            'discount_value' => $request->input('discount_value_percentage') / 100,
            'is_used' => $request->input('is_used', $coupon->is_used),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie zaktualizowany.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie usunięty.');
    }
}
