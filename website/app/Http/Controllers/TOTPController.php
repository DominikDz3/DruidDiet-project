<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use OTPHP\TOTP;

class TOTPController extends Controller
{
    public function showSetupForm(Request $request)
    {
        $user = Auth::user();
        if ($user->TOTP_secret) {
            return redirect()->route('user.totp.manage')
                ->with('info', 'Masz już skonfigurowane uwierzytelnianie dwuskładnikowe.');
        }

        $otp = TOTP::create(null, 30, 'sha1', 6);
        $secret = $otp->getSecret();
        $request->session()->put('totp_setup_secret', Crypt::encryptString($secret));

        return view('user.totp_setup', ['secret' => $secret]);
    }

        public function showManageForm()
    {
        $user = Auth::user();
        return view('user.manage_totp', compact('user'));
    }

 public function enableTOTP(Request $request)
    {
        $request->validate(['totp_code' => 'required|digits:6']);
        $user = Auth::user();
        $providedCode = $request->input('totp_code');

        if (!$request->session()->has('totp_setup_secret')) {
            return redirect()->route('user.totp.setup')->with('error', 'Sesja konfiguracji TOTP wygasła. Spróbuj ponownie.');
        }
        $secret = Crypt::decryptString($request->session()->get('totp_setup_secret'));
        $otp = TOTP::createFromSecret($secret);

        if ($otp->verify($providedCode)) {
            $user->TOTP_secret = Crypt::encryptString($secret);
            $user->save();
            $request->session()->forget('totp_setup_secret');
            return redirect()->route('user.totp.manage')->with('success', 'Uwierzytelnianie dwuskładnikowe (TOTP) zostało pomyślnie włączone!');
        } else {
            return redirect()->route('user.totp.setup')->withInput()->with('error', 'Podany kod TOTP jest nieprawidłowy.');
        }
    }

    public function disableTOTP(Request $request)
    {
        $user = Auth::user();
        $request->validate(['current_password_for_disable_totp' => 'required|string']);

        if (!Hash::check($request->input('current_password_for_disable_totp'), $user->password)) {
            return redirect()->route('user.totp.manage')->with('error_disable_totp', 'Podane hasło jest nieprawidłowe. TOTP nie zostało wyłączone.');
        }
        $user->TOTP_secret = null;
        $user->save();
        return redirect()->route('user.totp.manage')->with('success', 'Uwierzytelnianie dwuskładnikowe (TOTP) zostało wyłączone.');
    }
}