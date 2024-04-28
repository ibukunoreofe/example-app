<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(fn() => $this->map());
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapApiRoutes()
    {
        // Default the version to null initially
        $version = 'v1';
        $prefix = 'api';

        // Check if running in console to prevent errors during commands and tests
        if (!app()->runningInConsole()) {
            list($version, $prefix) = $this->getApiVersionAndBasePrefix($version, $prefix);
        }

        // Include the appropriate route file based on determined version
        Route::prefix($prefix)
            ->middleware('api')
            ->group(base_path("routes/api_$version.php"));
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function configureRateLimiting()
    {
        //  'throttle:30,1' 30 per 1 minute
        //  throttle uses cache. So if you clear cache. It will start again
        //  cache:clear

        // Limit::perDay(10, 5)->by($request->ip());
        // Means limit to 10 attempts in total within 5 days

        // You can add custom response as well
//        https://www.amitmerchant.com/new-ratelimiter-facade-in-laravel-8/
//        RateLimiter::for('book', function (Request $request) {
//            return Limit::perMinute(3)->response(function() {
//                return new Response('Beep! Beep! Too many attempts');
//            });
//        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('few', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(10)->by($request->user()->id)
                : Limit::perMinute(2)->by($request->ip());
        });
    }

    /**
     * @param string|null $defaultVersion
     * @return array|string|null
     */
    private function getApiVersionAndBasePrefix(string $defaultVersion = 'v1', string $defaultPrefix = 'api'): array
    {
        $request = request();
        $version = $defaultVersion;

        // Attempt to extract the version from the URI or header
        $uriSegments = explode('/', $request->path()); // Get URI segments to check for version in path

        if (isset($uriSegments[1]) && in_array($uriSegments[1], ['v1', 'v2'])) {
            $version = $uriSegments[1]; // Use version from URI if available
            return [ $version , $defaultPrefix . '/' . $version ];
        } else {
            $headerVersion = $request->header('Accept-Version');
            if ($headerVersion && in_array($headerVersion, ['v1', 'v2'])) {
                $version = $headerVersion; // Override with header version if specified
            }
        }

        return [ $version , $defaultPrefix  ];
    }
}
