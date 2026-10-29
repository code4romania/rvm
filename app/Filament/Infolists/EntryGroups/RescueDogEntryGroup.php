<?php

declare(strict_types=1);

namespace App\Filament\Infolists\EntryGroups;

use App\Filament\Infolists\Components\LocationEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class RescueDogEntryGroup extends EntryGroup
{
    public function getGroupKey(): string
    {
        return 'rescueDog';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextEntry::make('properties.dog_name')
                        ->label(__('resource.attributes.dog_name')),

                    TextEntry::make('types.name')
                        ->label(__('resource.attributes.type.dog')),

                    TextEntry::make('properties.volunteer_name')
                        ->label(__('resource.attributes.volunteer_name')),

                    TextEntry::make('properties.volunteer_specialization')
                        ->label(__('resource.attributes.volunteer_specialization')),

                    Group::make()
                        ->schema([
                            IconEntry::make('properties.dog_aircraft_cage')
                                ->label(__('resource.attributes.dog_aircraft_cage'))
                                ->boolean(),

                            IconEntry::make('properties.dog_trailer')
                                ->label(__('resource.attributes.dog_trailer'))
                                ->boolean(),
                        ]),
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
