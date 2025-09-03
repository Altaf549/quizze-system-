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
    public const HOME = '/admin/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Debug: Log that RouteServiceProvider is booting
        if (!app()->runningInConsole()) {
            $logFile = storage_path('logs/route_debug.log');
            file_put_contents($logFile, "[DEBUG] RouteServiceProvider booting...\n", FILE_APPEND);
        }

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Debug: Log that routes are being registered
            if (!app()->runningInConsole()) {
                $logFile = storage_path('logs/route_debug.log');
                file_put_contents($logFile, "[DEBUG] Registering routes...\n", FILE_APPEND);
            }
            // API Routes
            // Debug: Log before registering API routes
            if (!app()->runningInConsole()) {
                $logFile = storage_path('logs/route_debug.log');
                file_put_contents($logFile, "[DEBUG] Registering API routes...\n", FILE_APPEND);
            }

            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    // Debug: Log that we're about to include the API routes file
                    if (!app()->runningInConsole()) {
                        $logFile = storage_path('logs/route_debug.log');
                        file_put_contents($logFile, "[DEBUG] Including API routes file...\n", FILE_APPEND);
                    }
                    
                    require base_path('routes/api.php');
                    
                    // Debug: Log that we've included the API routes file
                    if (!app()->runningInConsole()) {
                        $logFile = storage_path('logs/route_debug.log');
                        file_put_contents($logFile, "[DEBUG] API routes file included\n", FILE_APPEND);
                    }
                });

            // Web Routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
