<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use OTPHP\TOTP;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

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

        return redirect()->route('login')->with('success', 'Rejestracja zakończona pomyślnie. Możesz się teraz zalogować.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user && !empty($user->TOTP_secret)) {
                Auth::logout();

                $request->session()->put('totp_user_id', $user->user_id);
                $request->session()->put('totp_remember', $remember);
                $request->session()->put('totp_intended_url', session()->pull('url.intended', $this->getRedirectUrlForRole($user->role)));

                return redirect()->route('login.totp.form');
            }

            return redirect()->intended($this->getRedirectUrlForRole($user->role));
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('email');
    }

    public function showTotpForm()
    {
        if (!session()->has('totp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.totp_verify');
    }

    public function verifyTotp(Request $request)
    {
        $request->validate([
            'totp_code' => 'required|digits:6',
        ]);

        if (!session()->has('totp_user_id')) {
            return redirect()->route('login')->withErrors(['totp_code' => 'Sesja weryfikacji TOTP wygasła. Spróbuj zalogować się ponownie.']);
        }

        $userId = session('totp_user_id');
        $user = User::find($userId);

        if (!$user || empty($user->TOTP_secret)) {
            session()->forget(['totp_user_id', 'totp_remember', 'totp_intended_url']);
            return redirect()->route('login')->withErrors(['totp_code' => 'Konfiguracja TOTP dla tego użytkownika jest niekompletna lub użytkownik nie istnieje.']);
        }

        try {
            $decryptedSecret = Crypt::decryptString($user->TOTP_secret);
            $otp = TOTP::createFromSecret($decryptedSecret);

            if ($otp->verify($request->input('totp_code'))) {
                $remember = session('totp_remember', false);
                $intendedUrl = session('totp_intended_url', $this->getRedirectUrlForRole($user->role));

                Auth::login($user, $remember);
                $request->session()->regenerate();

                session()->forget(['totp_user_id', 'totp_remember', 'totp_intended_url']);

                return redirect()->intended($intendedUrl);
            } else {
                return back()->withInput()->withErrors(['totp_code' => 'Podany kod TOTP jest nieprawidłowy.']);
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Błąd deszyfrowania TOTP_secret dla użytkownika ' . $userId . ': ' . $e->getMessage());
            session()->forget(['totp_user_id', 'totp_remember', 'totp_intended_url']);
            return redirect()->route('login')->withErrors(['totp_code' => 'Wystąpił krytyczny błąd z konfiguracją 2FA. Skontaktuj się z administratorem.']);
        } catch (\Exception $e) {
            Log::error('Błąd weryfikacji TOTP dla użytkownika ' . $userId . ': ' . $e->getMessage());
            return back()->withInput()->withErrors(['totp_code' => 'Wystąpił błąd podczas weryfikacji kodu TOTP. Spróbuj ponownie.']);
        }
    }

    private function getRedirectUrlForRole(?string $role): string 
    {
        $effectiveRole = $role ?? 'user';

        return match ($effectiveRole) {
            'admin' => route('admin.dashboard'),
            'user' => route('user.dashboard'), 
            default => route('home'),      
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
