<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\Organisations\OrganisationResource;
use App\Filament\Resources\Resources\ResourceResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Volunteers\VolunteerResource;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Resource;
use App\Models\User;
use App\Models\Volunteer;
use Filament\Actions\SelectAction;
use Filament\Schemas\Components\Component;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PlatformStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    /**
     * County id to filter by.
     *
     * @var null|string
     */
    public string $county = '';

    /**
     * Available counties to select.
     *
     * @var Collection
     */
    public Collection $counties;

    public function mount()
    {
        $this->counties = County::query()
            ->pluck('name', 'id');
    }

    public static function canView(): bool
    {
        return auth()->user()->isPlatformAdmin()
            || auth()->user()->isPlatformCoordinator();
    }

    public function getSectionContentComponent(): Component
    {
        return parent::getSectionContentComponent()
            ->headerActions([
                SelectAction::make('county')
                    ->placeholder('–')
                    ->options($this->counties),
            ]);
    }

    protected function getCards(): array
    {
        return [
            Stat::make(__('organisation.label.plural'), $this->getOrganisationCount())
                ->icon('heroicon-o-building-office')
                ->url(OrganisationResource::getUrl('index')),

            Stat::make(__('resource.label.plural'), $this->getResourceCount())
                ->icon('heroicon-o-rectangle-stack')
                ->url(ResourceResource::getUrl('index')),

            Stat::make(__('volunteer.label.plural'), $this->getVolunteerCount())
                ->icon('heroicon-o-user-group')
                ->url(VolunteerResource::getUrl('index')),

            Stat::make(__('user.label.plural'), $this->getUserCount())
                ->icon('heroicon-o-building-office')
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
