<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class DecodeSocialAuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * @throws \Exception
     */
    public function getUser(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', Rule::in([ "google", "facebook" ])],
            'token' => ['required', 'string',],
        ]);

        $type = $request->input('type');
        $token = $request->input('token');
        if( $type === 'google' )
            $social_user = $this->decodeGoogleOAuthToken( $token );
        else
            $social_user = $this->verifyWithSocialiteAuthToken( $type, $token );

//        /** @var  \Laravel\Socialite\Two\User|\Laravel\Socialite\Contracts\User $google_user **/
//        $google_user = Socialite::driver()->userFromToken();
//        return json_decode(json_encode($google_user));

        return $social_user?? throw new \Exception("Unfortunately, the token could not be decoded!");
    }


    /**
     * @param string $token
     * @return array|null
     */
    private function decodeGoogleOAuthToken(string $token)
    {
        return $this->verifyGoogleIdToken( $token )?? $this->verifyWithSocialiteAuthToken( 'google', $token );
    }

    /**
     * Seems this one is the only one that works with iOs Mobile
     * @param string $id_token
     * @return array|null
     */
    private function verifyGoogleIdToken(string $id_token)
    {
        try {
            // https://developers.google.com/identity/gsi/web/guides/verify-google-id-token
            $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
            $payload = $client->verifyIdToken($id_token);
            if ($payload) {
                // If request specified a G Suite domain:
                //$domain = $payload['hd'];
                return [
                    "social_id" => $payload['sub'],
                    "email" => $payload['email'],
                    "name" => $payload['name'],
                ];
            } else {
                return  null;
            }
        }catch (\Exception){
            return null;
        }
    }

    /**
     * @param string $type
     * @param string $auth_token
     * @return array|null
     */
    private function verifyWithSocialiteAuthToken(string $type, string $auth_token)
    {
        // this will throw an exception if failed
        try {
            /** @var $user \Laravel\Socialite\Two\User|\Laravel\Socialite\Contracts\User**/
            $user = Socialite::driver($type)->userFromToken($auth_token);
            return [
                "social_id" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getName(),
            ];
        }catch (\Exception){
            return null;
        }
    }
}
