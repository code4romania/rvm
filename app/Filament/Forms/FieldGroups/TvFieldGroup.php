<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Enum\Coverage;
use App\Filament\Forms\Components\Location;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

class TvFieldGroup extends FieldGroup
{
    public function getChildComponents(): array
    {
        return [
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    Select::make('properties.coverage')
                        ->label(__('resource.attributes.coverage.label'))
                        ->options(Coverage::options())
                        ->required()
                        ->columnSpanFull(),

                ]),

            Section::make(__('resource.fields.localisation'))
                ->schema([
                    Location::make()
                        ->withoutCity()
                        ->required(),
                ]),
        ];
    }
}
