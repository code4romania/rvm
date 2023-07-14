<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\ResourceResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VolunteerResource;
use App\Models\Organisation;
use App\Models\Resource;
use App\Models\User;
use App\Models\Volunteer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PlatformStatsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->isPlatformAdmin()
            || auth()->user()->isPlatformCoordinator();
    }

    protected function getCards(): array
    {
        return [
            Card::make(__('organisation.label.plural'), Organisation::count())
                ->icon('heroicon-o-office-building')
                ->url(OrganisationResource::getUrl('index')),

            Card::make(__('resource.label.plural'), Resource::count())
                ->icon('heroicon-o-collection')
                ->url(ResourceResource::getUrl('index')),

            Card::make(__('volunteer.label.plural'), Volunteer::count())
                ->icon('heroicon-o-user-group')
                ->url(VolunteerResource::getUrl('index')),

            Card::make(__('user.label.plural'), User::count())
                ->icon('heroicon-o-office-building')
                ->url(UserResource::getUrl('index')),
        ];
    }
}
