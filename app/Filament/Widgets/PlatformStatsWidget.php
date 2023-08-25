<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\ResourceResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VolunteerResource;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Resource;
use App\Models\User;
use App\Models\Volunteer;
use Filament\Forms\Components\Select;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PlatformStatsWidget extends BaseWidget
{
    protected static string $view = 'filament.widgets.platform-stats-widget';

    /**
     * County id to filter by.
     *
     * @var null|string
     */
    public string $county = '';

    /**
     * Available counties to select.
     *
     * @var \Illuminate\Support\Collection
     */
    public Collection $counties;

    public function mount()
    {
        $this->counties = County::all();
    }

    public static function canView(): bool
    {
        return auth()->user()->isPlatformAdmin()
            || auth()->user()->isPlatformCoordinator();
    }

    protected function getCards(): array
    {
        return [
            Card::make(__('organisation.label.plural'), $this->getOrganisationCount())
                ->icon('heroicon-o-office-building')
                ->url(OrganisationResource::getUrl('index')),

            Card::make(__('resource.label.plural'), $this->getResourceCount())
                ->icon('heroicon-o-collection')
                ->url(ResourceResource::getUrl('index')),

            Card::make(__('volunteer.label.plural'), $this->getVolunteerCount())
                ->icon('heroicon-o-user-group')
                ->url(VolunteerResource::getUrl('index')),

            Card::make(__('user.label.plural'), $this->getUserCount())
                ->icon('heroicon-o-office-building')
                ->url(UserResource::getUrl('index')),
        ];
    }

    protected function getOrganisationCount(): int
    {
        return Organisation::query()
            ->when($this->county, function (Builder $query) {
                $query->where('county_id', $this->county);
            })
            ->count();
    }

    public function getResourceCount(): int
    {
        return Resource::query()
            ->when($this->county, function (Builder $query) {
                $query->where('county_id', $this->county);
            })
            ->count();
    }

    public function getVolunteerCount(): int
    {
        return Volunteer::query()
            ->when($this->county, function (Builder $query) {
                $query->where('county_id', $this->county);
            })
            ->count();
    }

    public function getUserCount(): int
    {
        return User::query()
            ->when($this->county, function (Builder $query) {
                $query->whereRelation('organisation', 'county_id', $this->county);
            })
            ->count();
    }
}
