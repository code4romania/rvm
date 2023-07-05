<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Filament\Forms\Components\Location;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class RescueDogFieldGroup extends FieldGroup
{
    public function getChildComponents(): array
    {
        return [
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextInput::make('properties.dog_name')
                        ->label(__('resource.attributes.dog_name'))
                        ->maxLength(200)
                        ->required(),

                    Select::make('types')
                        ->relationship(
                            'types',
                            'name',
                            fn (Builder $query, callable $get) => $query
                                ->where('subcategory_id', $get('subcategory_id'))
                                ->orderBy('id')
                        )
                        ->label(__('resource.attributes.type.dog'))
                        ->multiple()
                        ->maxItems(1)
                        ->preload()
                        ->required(),

                    TextInput::make('properties.volunteer_name')
                        ->label(__('resource.attributes.volunteer_name'))
                        ->maxLength(200)
                        ->required(),

                    TextInput::make('properties.volunteer_specialization')
                        ->label(__('resource.attributes.volunteer_specialization'))
                        ->maxLength(200)
                        ->required(),

                    Group::make()
                        ->schema([
                            Checkbox::make('properties.dog_aircraft_cage')
                                ->label(__('resource.attributes.dog_aircraft_cage')),

                            Checkbox::make('properties.dog_trailer')
                                ->label(__('resource.attributes.dog_trailer')),
                        ]),
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
