<?php

namespace App\Http\Middleware\Versioning;

use Closure;

class AddVersionToResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $version = $request->header('Accept-Version', 'v1'); // default to v1 if not specified
        $response->headers->set('X-API-Version', $version);

        return $response;
    }
}
