<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateUserProfileRequest;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user.dashboard', compact('user'));
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profil został pomyślnie zaktualizowany.');
    }

    public function myCoupons()
    {
        $user = Auth::user();
        $coupons = $user->coupons()
                        ->where('is_used', false)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('user.my_coupons', compact('user', 'coupons'));
    }
}