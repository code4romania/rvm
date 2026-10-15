<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Settings;
use App\Filament\Pages\PersonalInfo;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckOrganisationIsActive;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login(Login::class)
            ->colors([
                'primary' => [
                    50 => '#EFF2FB',
                    100 => '#CED9F3',
                    200 => '#9DB3E7',
                    300 => '#3B67CE',
                    400 => '#264998',
                    500 => '#213E83',
                    600 => '#182F62',
                    700 => '#101F41',
                    800 => '#0C1731',
                    900 => '#040810',
                ],
                'secondary' => Color::Gray,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->brandLogo(fn () => Vite::asset('resources/images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckOrganisationIsActive::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->sort(1)
                    ->icon('heroicon-o-cog')
                    ->label(__('auth.settings'))
                    ->url(fn () => Settings::getUrl()),
            ])
            ->passwordReset()
            ->plugins([
                BreezyCore::make()
                    ->customMyProfilePage(Settings::class)
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens()
                    ->myProfile(slug: 'settings', shouldRegisterUserMenu: false)
                    ->myProfileComponents(['personal_info' => PersonalInfo::class]),
            ]);
    }
}
