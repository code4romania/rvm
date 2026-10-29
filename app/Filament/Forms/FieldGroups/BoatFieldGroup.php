<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Filament\Forms\Components\Location;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class BoatFieldGroup extends FieldGroup
{
    public function getGroupKey(): string
    {
        return 'boat';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextInput::make('properties.type')
                        ->label(__('resource.attributes.type.boat'))
                        ->maxLength(200)
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('properties.capacity')
                        ->label(__('resource.attributes.capacity'))
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    TextInput::make('properties.quantity')
                        ->label(__('resource.attributes.quantity'))
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                ]),

            Section::make(__('resource.fields.localisation'))
                ->schema([
                    Location::make()
                        ->columns()
                        ->required(),

                    Checkbox::make('properties.relocatable')
                        ->label(__('resource.attributes.location.relocatable')),

                    Checkbox::make('properties.transportable')
                        ->label(__('resource.attributes.location.transportable')),
                ]),
        ]);
    }
}
