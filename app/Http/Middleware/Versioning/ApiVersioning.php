<?php

namespace App\Http\Middleware\Versioning;

use Closure;

class ApiVersioning
{
    public function handle($request, Closure $next)
    {
        $version = $request->header('Accept-Version');

        switch ($version) {
            case 'v1':
                require base_path('routes/api_v1.php');
                break;
            case 'v2':
                require base_path('routes/api_v2.php');
                break;
            default:
                // handle no version or default version
                require base_path('routes/api_v1.php');
                break;
        }
        return $next($request);
    }
}