<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Models\City;
use App\Models\County;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Cache;

class LocationEntry extends Grid
{
    protected bool $withCity = true;

    public function withoutCity(): self
    {
        $this->withCity = false;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $county = TextEntry::make('county_id')
            ->label(__('general.county'))
            ->formatStateUsing(function ($state) {
                $counties = Cache::driver('array')
                    ->rememberForever(
                        'counties',
                        fn () => County::pluck('name', 'id')
                    );

                return $counties[$state];
            });

        $components = [
            ! $this->withCity
                ? $county->columnSpanFull()
                : $county,
        ];

        if ($this->withCity) {
            $components[] = TextEntry::make('city_id')
                ->label(__('general.city'))
                ->formatStateUsing(function ($state) {
                    $city = City::query()
                        ->where('id', $state)
                        ->get()
                        ->pluck('name', 'id')
                        ->first();

                    return $city;
                });
        }

        $this->schema($components);
    }
}
