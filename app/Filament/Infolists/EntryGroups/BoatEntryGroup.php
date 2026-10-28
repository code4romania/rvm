<?php

declare(strict_types=1);

namespace App\Filament\Infolists\EntryGroups;

use App\Filament\Infolists\Components\LocationEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class BoatEntryGroup extends EntryGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextEntry::make('properties.type')
                        ->label(__('resource.attributes.type.boat'))
                        ->columnSpanFull(),

                    TextEntry::make('properties.capacity')
                        ->label(__('resource.attributes.capacity'))
                        ->numeric(),

                    TextEntry::make('properties.quantity')
                        ->label(__('resource.attributes.quantity'))
                        ->numeric(),
                ]),

            Section::make(__('resource.fields.localisation'))
                ->schema([
                    LocationEntry::make()
                        ->columns(),

                    IconEntry::make('properties.relocatable')
                        ->label(__('resource.attributes.location.relocatable'))
                        ->boolean(),

                    IconEntry::make('properties.transportable')
                        ->label(__('resource.attributes.location.transportable'))
                        ->boolean(),
                ]),
        ]);
    }
}
