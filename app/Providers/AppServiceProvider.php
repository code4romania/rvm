<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Pages\Auth\Settings;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCarbonMacros();
        $this->registerReleaseVersion();

        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        tap($this->app->isLocal(), function (bool $shouldBeEnabled) {
            Model::preventLazyLoading($shouldBeEnabled);
            Model::preventAccessingMissingAttributes($shouldBeEnabled);
        });

        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/app.css');

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()
                    ->url(Settings::getUrl())
                    ->label(__('auth.settings'))
                    ->icon('heroicon-o-cog'),
            ]);
        });

        Gate::define('viewApiDocs', function (User $user) {
            return $user->canAccessFilament() && $user->isPlatformAdmin();
        });
    }

    public function registerReleaseVersion(): void
    {
        $version = rescue(fn () => File::get(base_path('.version')), 'develop', report: false);

        config([
            'sentry.release' => $version,
        ]);
    }

    protected function registerCarbonMacros(): void
    {
        Carbon::macro('toFormattedDate', fn () => $this->translatedFormat(config('forms.components.date_time_picker.display_formats.date')));
        Carbon::macro('toFormattedDateTime', fn () => $this->translatedFormat(config('forms.components.date_time_picker.display_formats.date_time')));
        Carbon::macro('toFormattedDateTimeWithSeconds', fn () => $this->translatedFormat(config('forms.components.date_time_picker.display_formats.date_time_with_seconds')));
        Carbon::macro('toFormattedTime', fn () => $this->translatedFormat(config('forms.components.date_time_picker.display_formats.time')));
        Carbon::macro('toFormattedTimeWithSeconds', fn () => $this->translatedFormat(config('forms.components.date_time_picker.display_formats.time_with_seconds')));
    }
}
