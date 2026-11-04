<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use ReflectionClass;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->registerHealthRoute();

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware([
                'web',
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
                ->name('filament.')
                ->group(base_path('routes/filament.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Register Laravel's health route outside the web middleware group.
     *
     * @return void
     */
    protected function registerHealthRoute()
    {
        Route::get('/up', function () {
            $exception = null;

            try {
                Event::dispatch(new DiagnosingHealth);
            } catch (\Throwable $e) {
                if (app()->hasDebugModeEnabled()) {
                    throw $e;
                }

                report($e);

                $exception = $e->getMessage();
            }

            $view = dirname((new ReflectionClass(ApplicationBuilder::class))->getFileName(), 2) . '/resources/health-up.blade.php';

            return response(View::file($view, [
                'exception' => $exception,
            ]), status: $exception ? 500 : 200);
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
