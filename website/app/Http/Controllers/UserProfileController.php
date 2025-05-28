<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateUserProfileRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

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
        
        if (isset($validatedData['allergens'])) {
            $user->allergens = $validatedData['allergens'];
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

    public function generateChangeTokenForConsole()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors('Musisz być zalogowany, aby wygenerować token.');
        }

        try {
            $token = Password::broker()->createToken($user);

            session()->flash('token_for_f12_console', $token);
            session()->flash('f12_token_info', 'Token do zmiany hasła został wygenerowany! Otwórz konsolę deweloperską przeglądarki (F12 lub Ctrl+Shift+I / Cmd+Opt+I, zakładka "Console") i skopiuj go. Token jest jednorazowy i ważny przez określony czas (domyślnie 60 minut).');

            return back();
        } catch (\Exception $e) {
            return back()->withErrors('Wystąpił błąd podczas generowania tokenu. Spróbuj ponownie.');
        }
    }

    public function updatePasswordWithToken(Request $request)
    {
        $request->validate([
            'token_f12' => 'required|string',
            'password_f12' => 'required|string|confirmed|min:8',
            'password_f12_confirmation' => 'required|string',
        ], [
            'token_f12.required' => 'Pole token jest wymagane.',
            'password_f12.required' => 'Nowe hasło jest wymagane.',
            'password_f12.confirmed' => 'Potwierdzenie nowego hasła nie zgadza się.',
            'password_f12.min' => 'Nowe hasło musi mieć co najmniej :min znaków.',
        ]);

        $user = Auth::user();

        $credentials = [
            'email' => $user->email,
            'token' => $request->token_f12,
            'password' => $request->password_f12,
            'password_confirmation' => $request->password_f12_confirmation,
        ];

        $response = Password::broker()->reset($credentials, function ($userObject, $password) {
            $userObject->password = Hash::make($password);
            $userObject->save();
            event(new PasswordReset($userObject));
        });

        if ($response == Password::PASSWORD_RESET) {
            return back()->with('success_password_f12', trans('passwords.reset'));
        }
        
        return back()->withErrors(['token_f12_error' => trans($response)])->withInput($request->only('token_f12'));
    }
}