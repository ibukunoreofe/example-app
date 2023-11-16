<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function redirectForLogin(): RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle an incoming authentication request.
     */
    public function handleGoogleCallback(Request $request)
    {
        $google_user = Socialite::driver('google')->user();
        $user = User::getOrCreateUserViaSocialiteUser($google_user);
        Auth::login($user);
        return redirect()->intended('dashboard');
    }
}
