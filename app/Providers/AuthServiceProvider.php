<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // This prevents an infinite loop when using the
        // `LimitsVisibility` trait on the `User` model
        Auth::provider('eloquent', function ($app, array $config) {
            return (new EloquentUserProvider($app['hash'], $config['model']))
                ->withQuery(fn (Builder $query) => $query->withoutGlobalScopes());
        });

        Gate::define('accessApi', function (User $user) {
            if (! config('filament-breezy.enable_sanctum')) {
                return false;
            }

            return $user->isPlatformAdmin();
        });
    }
}
