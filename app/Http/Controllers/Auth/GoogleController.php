<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Error al autenticar con Google.']);
        }

        // Solo permite acceso si el correo ya está registrado por el admin
        $user = User::where('email', $googleUser->getEmail())
                    ->orWhere('google_id', $googleUser->getId())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta de Google no está registrada. Contacta al administrador.',
            ]);
        }

        // Actualiza datos de Google si es primera vez
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        Auth::login($user, true);
        return redirect()->route('dashboard');
    }
}
