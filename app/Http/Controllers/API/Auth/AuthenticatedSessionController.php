<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\OkResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): OkResponse
    {
        $request->authenticate();
        return self::respondWithUserAndSanctumToken($request->user());
        //        $request->session()->regenerate();
        //        return response()->noContent();
    }

    public static function respondWithUserAndSanctumToken(User $user)
    {
        return new OkResponse([
            'user' => $user->toArray(),
            'token' => $user->createToken('API')->plainTextToken,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        //        Auth::guard('web')->logout();
        //
        //        $request->session()->invalidate();
        //
        //        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
