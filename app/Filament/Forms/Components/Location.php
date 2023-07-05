<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Models\City;
use App\Models\County;
use Filament\Forms\Components\Concerns\CanBeValidated;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

class Location extends Grid
{
    use CanBeValidated;

    protected bool $withCity = true;

    public function withoutCity(): self
    {
        $this->withCity = false;

        return $this;
    }

    public function getChildComponents(): array
    {
        $county = Select::make('county_id')
            ->label(__('general.county'))
            ->options(function () {
                return Cache::driver('array')
                    ->rememberForever(
                        'counties',
                        fn () => County::pluck('name', 'id')
                    );
            })
            ->searchable()
            ->reactive()
            ->required($this->isRequired())
            ->afterStateUpdated(fn (callable $set) => $set('city_id', null));

        $components = [
            ! $this->withCity
                ? $county->columnSpanFull()
                : $county,
        ];

        if ($this->withCity) {
            $components[] = Select::make('city_id')
                ->label(__('general.city'))
                ->searchable()
                ->required($this->isRequired())
                ->getSearchResultsUsing(function (string $search, callable $get) {
                    $countyId = (int) $get('county_id');

                    debug(\func_get_args());

                    if (! $countyId) {
                        return [];
                    }

                    return City::query()
                        ->where('county_id', $countyId)
                        ->search($search)
                        ->limit(100)
                        ->get()
                        ->pluck('name', 'id');
                })
                ->getOptionLabelUsing(
                    fn ($value) => City::find($value)?->name
                );
        }

        return $components;
    }
}
