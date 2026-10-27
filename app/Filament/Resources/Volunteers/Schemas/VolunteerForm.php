<?php

namespace App\Filament\Resources\Volunteers\Schemas;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Forms\Components\Location;
use App\Rules\ValidCNP;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VolunteerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns()
                    ->schema([
                        Group::make()
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                Select::make('organisation_id')
                                    ->label(__('volunteer.field.organisation'))
                                    ->relationship('organisation', 'name')
                                    ->required(),
                            ]),

                        TextInput::make('last_name')
                            ->label(__('volunteer.field.last_name'))
                            ->maxLength(200)
                            ->required(),

                        TextInput::make('first_name')
                            ->label(__('volunteer.field.first_name'))
                            ->maxLength(200)
                            ->required(),

                        TextInput::make('email')
                            ->label(__('volunteer.field.email'))
                            ->maxLength(200)
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label(__('volunteer.field.phone'))
                            ->maxLength(14)
                            ->tel(),

                        TextInput::make('cnp')
                            ->label(__('volunteer.field.cnp'))
                            ->rule(new ValidCNP)
                            ->unique(ignoreRecord: true),

                        Select::make('role')
                            ->label(__('volunteer.field.role'))
                            ->options(VolunteerRole::class)
                            ->enum(VolunteerRole::class)
                            ->required(),

                        Select::make('specializations')
                            ->label(__('volunteer.field.specializations'))
                            ->options(VolunteerSpecialization::options())
                            ->multiple()
                            ->required()
                            ->live(),

                        TextInput::make('language')
                            ->label(__('volunteer.field.language'))
                            ->maxLength(200)
                            ->visible(
                                fn (callable $get) => \in_array('translator', $get('specializations'))
                            ),

                        Toggle::make('has_first_aid_accreditation')
                            ->label(__('volunteer.field.has_first_aid_accreditation'))
                            ->columnSpanFull(),

                        Location::make()
                            ->required(),
                    ]),
            ]);
    }
}
