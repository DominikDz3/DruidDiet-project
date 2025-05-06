<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
// Formularz rejestracji
public function register()
{
    return view('auth.register');
}

// Obsługa rejestracji
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);

    Auth::login($user);
    return $this->redirectByRole($user);
}

    // Formularz logowania
    public function login()
    {
        return view('auth.login');
    }

    // Obsługa logowania
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowe dane logowania.',
        ])->withInput();
    }

    private function redirectByRole(User $user)
    {
    return match ($user->role) {
        'admin' => redirect('/admin/dashboard'),
        'user' => redirect('/user/dashboard'),
        default => redirect('/'),
        };
    }

    // Wylogowywanie
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
