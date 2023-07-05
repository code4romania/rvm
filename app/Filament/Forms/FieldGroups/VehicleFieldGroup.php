<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Filament\Forms\Components\Location;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class VehicleFieldGroup extends FieldGroup
{
    public function getChildComponents(): array
    {
        return [
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    Select::make('types')
                        ->relationship(
                            'types',
                            'name',
                            fn (Builder $query, callable $get) => $query
                                ->where('subcategory_id', $get('subcategory_id'))
                                ->orderBy('id')
                        )
                        ->label(__('resource.attributes.type.vehicle'))
                        ->multiple()
                        ->maxItems(1)
                        ->preload()
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
        ];
    }
}
