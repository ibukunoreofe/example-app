<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class DecodeSocialAuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function getUser(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', Rule::in([ "google", "facebook" ])],
            'token' => ['required', 'string',],
        ]);

        /** @var  \Laravel\Socialite\Two\User|\Laravel\Socialite\Contracts\User $google_user **/
        $google_user = Socialite::driver($request->input('type'))->userFromToken($request->input('token'));
        return json_decode(json_encode($google_user));
    }
}
