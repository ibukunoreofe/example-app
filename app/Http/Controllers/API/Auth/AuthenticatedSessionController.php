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
     * @OA\Post(
     * path = "/login",
     * summary = "Log in",
     * description = "Login by email and password",
     * operationId = "loginAuthorization",
     * tags = {
     *       "Authentication"
     *       },
     *  @OA\RequestBody(
     *    required = true,
     *    description = "Enter login details",
     *    @OA\JsonContent(
     *       required ={"email","password"},
     *   @OA\Property(property = "email", type = "string",
     *       format = "email", pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$", example = "ibukunoreofe@gmail.com"),
     *       @OA\Property(property = "password", type = "string", format = "password", example = "pp"),
     *    ),
     * ),
     * @OA\Response(
     *     response = 200,
     *     description = "Success",
     *     @OA\JsonContent(
     *        @OA\Property(property = "token", type = "string", example = "longstring"),
     *        @OA\Property(property = "result", type = "string", example = "success"),
     *     )
     *  ),
     * @OA\Response(
     *    response = 422,
     *    description = "Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property = "message", type = "string",
     *      example = "The email or password entered are not recognised"),
     *       @OA\Property(property = "result", type = "string", example = "error"),
     *        )
     *     )
     * )
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
