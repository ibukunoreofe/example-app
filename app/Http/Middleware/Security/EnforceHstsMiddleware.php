<?php

namespace App\Http\Middleware\Security;

use Closure;

class EnforceHstsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only apply HSTS if HTTPS is used
        if (!$request->isSecure()) {
            return $response;
        }

        // Set HSTS header
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );

        return $response;
    }
}
