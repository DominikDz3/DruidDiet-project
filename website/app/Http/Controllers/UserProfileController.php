<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateUserProfileRequest;

class UserProfileController extends Controller
{
    /**
     * Show the form for editing the current user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.dashboard', compact('user'));
    }

    /**
     * Update the current user's profile in storage.
     *
     * @param  \App\Http\Requests\User\UpdateUserProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserProfileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];
        $user->allergens = $validatedData['allergens'] ?? $user->allergens;

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profil został pomyślnie zaktualizowany.');
    }
}
