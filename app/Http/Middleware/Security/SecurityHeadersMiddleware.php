<?php

namespace App\Http\Middleware\Security;

use Closure;

class SecurityHeadersMiddleware
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

        // Prevent Clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');

        // Content Security Policy
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; object-src 'none';");

        // Handle CORS preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            // Allow OPTIONS, required for CORS preflight
            $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT, DELETE'); // List all methods your API supports
            return $response;
        }

        // Method Limiting based on actual request methods
        // Note: Customize this based on the endpoints and methods your API supports
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
        $response->headers->set('Allow', implode(', ', $allowedMethods));

        return $response;
    }
}
