<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;

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

        $county = TextEntry::make('county.name')
            ->label(__('general.county'));

        $components = [
            ! $this->withCity
                ? $county->columnSpanFull()
                : $county,
        ];

        if ($this->withCity) {
            $components[] = TextEntry::make('city.name')
                ->label(__('general.city'));
        }

        $this->schema($components);
    }
}
