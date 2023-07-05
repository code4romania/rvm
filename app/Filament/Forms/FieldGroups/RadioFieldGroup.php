<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Filament\Forms\Components\Location;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class RadioFieldGroup extends FieldGroup
{
    public function getChildComponents(): array
    {
        return [
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextInput::make('properties.type')
                        ->label(__('resource.attributes.type.radio'))
                        ->maxLength(200)
                        ->required()
                        ->columnSpanFull(),

                ]),

            Section::make(__('resource.fields.localisation'))
                ->schema([
                    Location::make()
                        ->columns()
                        ->required(),
                ]),
        ];
    }
}
