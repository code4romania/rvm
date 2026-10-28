<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\Schemas;

use App\Enum\NGOType;
use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrganisationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('alias'),
                Select::make('type')
                    ->options(OrganisationType::class)
                    ->required(),
                Select::make('ngo_type')
                    ->options(NGOType::class),
                Select::make('status')
                    ->options(OrganisationStatus::class)
                    ->default('invited')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('year'),
                TextInput::make('cif'),
                TextInput::make('registration_number'),
                Select::make('county_id')
                    ->relationship('county', 'name'),
                Select::make('city_id')
                    ->relationship('city', 'name'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('contact_person'),
                TextInput::make('contact_person_in_teams'),
                TextInput::make('other_information'),
                TextInput::make('type_of_area'),
                Select::make('area')
                    ->options(OrganisationAreaType::class),
                Toggle::make('has_branches')
                    ->required(),
                Toggle::make('social_services_accreditation')
                    ->required(),
            ]);
    }
}
