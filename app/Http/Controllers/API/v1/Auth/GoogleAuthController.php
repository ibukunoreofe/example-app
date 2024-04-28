<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function loginViaGoogleToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string',],
        ]);

        $google_user = Socialite::driver('google')->userFromToken($request->input('token'));
        $user = User::getOrCreateUserViaSocialiteUser($google_user);

        return AuthenticatedSessionController::respondWithUserAndSanctumToken($user);
    }
}
