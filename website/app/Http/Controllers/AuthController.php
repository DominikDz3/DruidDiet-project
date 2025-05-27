<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt; // Do odszyfrowania sekretu TOTP
use App\Models\User;
use OTPHP\TOTP; // Do weryfikacji kodu TOTP
use Illuminate\Support\Facades\Log; // Opcjonalnie, do logowania błędów

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
            'role' => 'user', // Domyślna rola
            // TOTP_secret jest ustawiany później przez użytkownika w jego profilu
        ]);

        return redirect()->route('login')->with('success', 'Rejestracja zakończona pomyślnie. Możesz się teraz zalogować.');
    }

    // Formularz logowania
    public function login()
    {
        return view('auth.login');
    }

    // Obsługa logowania (zrefaktoryzowana)
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        // Próba zalogowania użytkownika
        if (Auth::attempt($credentials, $remember)) {
            // Pomyślnie zautentykowano (hasło pasuje)
            // Regeneracja ID sesji jest ważna dla bezpieczeństwa
            $request->session()->regenerate();

            $user = Auth::user(); // Pobierz aktualnie zautentykowanego użytkownika

            // Sprawdź, czy użytkownik istnieje i ma włączone TOTP
            if ($user && !empty($user->TOTP_secret)) {
                // Użytkownik ma włączone TOTP. Musimy go tymczasowo wylogować
                // i przekierować do formularza weryfikacji TOTP.
                Auth::logout(); // Wyloguj, ponieważ Auth::attempt() utworzyło sesję

                // Zapisz potrzebne informacje w sesji do etapu weryfikacji TOTP
                $request->session()->put('totp_user_id', $user->user_id);
                $request->session()->put('totp_remember', $remember); // Zachowaj informację o "zapamiętaj mnie"
                // Zapisz URL, do którego użytkownik chciał przejść, lub domyślny URL po roli
                $request->session()->put('totp_intended_url', session()->pull('url.intended', $this->getRedirectUrlForRole($user->role)));

                return redirect()->route('login.totp.form');
            }

            // TOTP nie jest włączone lub nie jest skonfigurowane. Użytkownik jest już zalogowany.
            // Przekieruj do zamierzonego URL lub domyślnego URL na podstawie roli.
            return redirect()->intended($this->getRedirectUrlForRole($user->role));
        }

        // Autentykacja nie powiodła się (niepoprawne dane logowania)
        return back()->withErrors([
            'email' => 'Nieprawidłowe dane logowania.',
        ])->onlyInput('email');
    }

    /**
     * Wyświetla formularz do podania kodu TOTP.
     */
    public function showTotpForm()
    {
        // Upewnij się, że użytkownik przeszedł pierwszy etap logowania
        if (!session()->has('totp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.totp_verify'); // Upewnij się, że masz ten widok
    }

    /**
     * Weryfikuje podany kod TOTP i loguje użytkownika.
     */
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
            // Usuń dane sesji TOTP, jeśli coś jest nie tak
            session()->forget(['totp_user_id', 'totp_remember', 'totp_intended_url']);
            return redirect()->route('login')->withErrors(['totp_code' => 'Konfiguracja TOTP dla tego użytkownika jest niekompletna lub użytkownik nie istnieje.']);
        }

        try {
            $decryptedSecret = Crypt::decryptString($user->TOTP_secret);
            $otp = TOTP::createFromSecret($decryptedSecret); // Użyj tych samych parametrów co przy create() w TOTPController

            if ($otp->verify($request->input('totp_code'))) {
                // Kod TOTP jest poprawny
                $remember = session('totp_remember', false);
                // Pobierz docelowy URL, domyślnie na podstawie roli użytkownika
                $intendedUrl = session('totp_intended_url', $this->getRedirectUrlForRole($user->role));

                Auth::login($user, $remember); // Zaloguj użytkownika
                $request->session()->regenerate(); // Ważne dla bezpieczeństwa

                // Usuń dane TOTP z sesji po pomyślnym zalogowaniu
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

    /**
     * Zwraca URL przekierowania na podstawie roli użytkownika.
     * Uczyniono parametr $role nullable i dodano domyślną logikę.
     */
    private function getRedirectUrlForRole(?string $role): string // Pozwalamy na null dla roli
    {
        // Jeśli rola jest null, domyślnie przekieruj jak dla 'user' lub na 'home'
        $effectiveRole = $role ?? 'user'; // Możesz zmienić 'user' na inną domyślną rolę lub logikę

        return match ($effectiveRole) {
            'admin' => route('admin.dashboard'), // Upewnij się, że masz tę nazwaną trasę
            'user' => route('user.dashboard'),   // Upewnij się, że masz tę nazwaną trasę
            default => route('home'),             // Domyślna trasa 'home'
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
