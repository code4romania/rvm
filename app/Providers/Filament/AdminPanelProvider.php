<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Settings;
use App\Filament\Pages\PersonalInfo;
use App\Filament\Widgets as CustomWidgets;
use App\Http\Middleware\CheckOrganisationIsActive;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
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
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->viteTheme('resources/css/app.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                CustomWidgets\AccountWidget::class,
                CustomWidgets\OrganisationStatsWidget::class,
                CustomWidgets\PlatformStatsWidget::class,
            ])
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
                CheckOrganisationIsActive::class,
                Authenticate::class,
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
