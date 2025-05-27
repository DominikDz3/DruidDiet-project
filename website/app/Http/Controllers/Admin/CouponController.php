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

    public function create(Request $request)
    {
        $users = User::orderBy('name')->orderBy('surname')->get();
        $selectedUserId = $request->query('user_id');

        return view('admin.coupons.create', compact('users', 'selectedUserId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_type' => 'required|in:auto,manual',
            'code' => 'nullable|required_if:code_type,manual|string|max:50|unique:coupons,code',
            'discount_value_percentage' => 'required|numeric|min:1|max:100',
            'user_id' => 'nullable|exists:users,user_id',
            'is_used' => 'nullable|boolean',
        ], [
            'code.required_if' => 'Pole kod jest wymagane, jeśli wybrano ręczne generowanie.',
            'code.unique' => 'Ten kod już istnieje w systemie.',
            'discount_value_percentage.required' => 'Wartość rabatu jest wymagana.',
            'discount_value_percentage.numeric' => 'Wartość rabatu musi być liczbą.',
            'discount_value_percentage.min' => 'Wartość rabatu musi wynosić co najmniej 1%.',
            'discount_value_percentage.max' => 'Wartość rabatu nie może przekraczać 100%.',
            'user_id.exists' => 'Wybrany użytkownik nie istnieje.',
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
            'discount_value' => $request->input('discount_value_percentage') / 100,
            'is_used' => $request->input('is_used', false),
            'user_id' => $request->input('user_id'),
            'created_at' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie utworzony.');
    }

    public function show(Coupon $coupon)
    {
        $users = User::orderBy('name')->orderBy('surname')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }


    public function edit(Coupon $coupon)
    {
        $users = User::orderBy('name')->orderBy('surname')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->coupon_id . ',coupon_id',
            'discount_value_percentage' => 'required|numeric|min:1|max:100',
            'user_id' => 'nullable|exists:users,user_id',
            'is_used' => 'nullable|boolean',
        ],[
            'code.unique' => 'Ten kod już istnieje w systemie.',
            'discount_value_percentage.required' => 'Wartość rabatu jest wymagana.',
            'discount_value_percentage.numeric' => 'Wartość rabatu musi być liczbą.',
            'discount_value_percentage.min' => 'Wartość rabatu musi wynosić co najmniej 1%.',
            'discount_value_percentage.max' => 'Wartość rabatu nie może przekraczać 100%.',
            'user_id.exists' => 'Wybrany użytkownik nie istnieje.',
        ]);

        $coupon->update([
            'code' => strtoupper($request->input('code')),
            'discount_value' => $request->input('discount_value_percentage') / 100,
            'is_used' => $request->input('is_used', $coupon->is_used),
            'user_id' => $request->input('user_id'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie zaktualizowany.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kod rabatowy został pomyślnie usunięty.');
    }

    public function showRandomUserForm()
    {
        return view('partials.random_user_form');
    }

    public function generateForRandomUser(Request $request)
    {
        $request->validate([
            'discount_value_percentage' => 'required|numeric|min:1|max:100',
        ], [
            'discount_value_percentage.required' => 'Wartość rabatu jest wymagana.',
            'discount_value_percentage.numeric' => 'Wartość rabatu musi być liczbą.',
            'discount_value_percentage.min' => 'Wartość rabatu musi wynosić co najmniej 1%.',
            'discount_value_percentage.max' => 'Wartość rabatu nie może przekraczać 100%.',
        ]);

        $randomUser = User::where('role', 'user')->inRandomOrder()->first();

        if (!$randomUser) {
            $randomUser = User::inRandomOrder()->first();
            if (!$randomUser) {
                return redirect()->back()->withInput()->with('error', 'Nie znaleziono żadnych użytkowników w systemie, aby przypisać kod.');
            }
        }

        $code = strtoupper(Str::random(10));
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(10));
        }

        $coupon = Coupon::create([
            'code' => $code,
            'discount_value' => $request->input('discount_value_percentage') / 100,
            'is_used' => false,
            'user_id' => $randomUser->user_id,
            'created_at' => Carbon::now()->toDateString(),
        ]);

        $successMessage = "Kod rabatowy '<strong>{$coupon->code}</strong>' ({$request->input('discount_value_percentage')}%) został pomyślnie wygenerowany i przypisany do użytkownika: <strong>{$randomUser->name} {$randomUser->surname}</strong> (ID: {$randomUser->user_id}).";

        return redirect()->route('admin.coupons.randomUserForm')
            ->with('success', $successMessage);
    }
}