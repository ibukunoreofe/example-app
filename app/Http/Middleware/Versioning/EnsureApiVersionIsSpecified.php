<?php

namespace App\Http\Middleware\Versioning;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EnsureApiVersionIsSpecified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Extract version from the URL
        $uriSegments = explode('/', trim($request->path(), '/'));
        $versionFromURL = isset($uriSegments[1]) && in_array($uriSegments[1], ['v1', 'v2']) ? $uriSegments[1] : null;

        // Extract version from the header
        $versionFromHeader = $request->header('Accept-Version');
        $versionFromHeader = in_array($versionFromHeader, ['v1', 'v2']) ? $versionFromHeader : null;

        // Check if a valid version is specified either in the URL or the header
        if (!$versionFromURL && !$versionFromHeader) {
            return response()->json(['error' => 'API version must be specified either in the URL or as a header.'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
