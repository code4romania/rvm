<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\Schemas;

use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationType;
use App\Filament\Infolists\Components\LocationEntry;
use App\Models\Organisation;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrganisationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(2)
                    ->schema([
                        Section::make(__('organisation.section.organisation_data'))
                            ->columns()
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('organisation.field.name'))
                                    ->placeholder(__('organisation.placeholder.name')),

                                TextEntry::make('alias')
                                    ->label(__('organisation.field.alias'))
                                    ->placeholder(__('organisation.placeholder.alias')),

                                TextEntry::make('type')
                                    ->label(__('organisation.field.type')),

                                TextEntry::make('ngo_type')
                                    ->label(__('organisation.field.ngo_type'))
                                    ->visible(fn (Get $get) => OrganisationType::ngo->is($get('type'))),

                                TextEntry::make('year')
                                    ->label(__('organisation.field.year'))
                                    ->placeholder('2006')
                                    ->numeric(),

                                TextEntry::make('cif')
                                    ->label(__('organisation.field.cif')),

                                TextEntry::make('registration_number')
                                    ->label(__('organisation.field.registration_number')),

                                TextEntry::make('email')
                                    ->label(__('organisation.field.email_organisation')),

                                TextEntry::make('phone')
                                    ->label(__('organisation.field.phone_organisation')),

                                TextEntry::make('description')
                                    ->label(__('organisation.field.short_description'))
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('organisation.section.contact_person'))
                            ->columns()
                            ->schema([
                                TextEntry::make('contact_person.first_name')
                                    ->label(__('organisation.field.contact_person_first_name')),

                                TextEntry::make('contact_person.last_name')
                                    ->label(__('organisation.field.contact_person_last_name')),

                                TextEntry::make('contact_person.role')
                                    ->label(__('organisation.field.role'))
                                    ->columnSpanFull(),

                                TextEntry::make('contact_person.email')
                                    ->label(__('organisation.field.email')),

                                TextEntry::make('contact_person.phone')
                                    ->label(__('organisation.field.phone')),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make(__('organisation.field.logo'))
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('logo')
                                    ->disk('s3-public')
                                    ->conversion('thumb')
                                    ->visibility('public')
                                    ->hiddenLabel(),
                            ]),

                        Section::make(__('organisation.field.hq'))
                            ->schema([
                                LocationEntry::make()
                                    ->columns(1),

                                TextEntry::make('address')
                                    ->label(__('organisation.field.address'))
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('organisation.field.other_information'))
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('other_information.website')
                                    ->label(__('organisation.field.website'))
                                    ->url(fn (Organisation $record) => Str::sanitizeUrl($record->other_information['website'])),

                                TextEntry::make('other_information.facebook')
                                    ->label(__('organisation.field.facebook'))
                                    ->url(fn (Organisation $record) => Str::sanitizeUrl($record->other_information['facebook'])),
                            ]),
                    ]),

                Section::make(__('organisation.section.contact_person_in_teams'))
                    ->columns()
                    ->schema([
                        TextEntry::make('contact_person_in_teams.first_name')
                            ->label(__('organisation.field.contact_person_in_teams_first_name')),

                        TextEntry::make('contact_person_in_teams.last_name')
                            ->label(__('organisation.field.contact_person_in_teams_last_name')),

                        TextEntry::make('contact_person_in_teams.role')
                            ->label(__('organisation.field.role'))
                            ->columnSpanFull(),

                        TextEntry::make('contact_person_in_teams.email')
                            ->label(__('organisation.field.email')),

                        TextEntry::make('contact_person_in_teams.phone')
                            ->label(__('organisation.field.phone')),
                    ]),

                Section::make(__('organisation.section.activity'))
                    ->columns()
                    ->schema([
                        TextEntry::make('expertises')
                            ->label(__('organisation.field.expertises'))
                            ->formatStateUsing(fn ($state) => $state->name),

                        TextEntry::make('riskcategories')
                            ->label(__('organisation.field.risk_category'))
                            ->formatStateUsing(fn ($state) => $state->name),

                        TextEntry::make('resourcetypes')
                            ->label(__('organisation.field.resource_types'))
                            ->formatStateUsing(fn ($state) => $state->name)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('organisation.section.area_of_activity'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('area')
                            ->label(__('organisation.field.area'))
                            ->afterStateUpdated(function (string $state, callable $set) {
                                if (
                                    OrganisationAreaType::regional->isNot($state) &&
                                    OrganisationAreaType::local->isNot($state)
                                ) {
                                    $set('activity_counties', []);
                                }

                                if (OrganisationAreaType::local->isNot($state)) {
                                    $set('activity_cities', []);
                                }
                            }),

                        TextEntry::make('activity_counties.county')
                            ->label(__('general.county'))
                            ->visible(fn (callable $get) => \in_array($get('area'), [
                                OrganisationAreaType::regional->value,
                                OrganisationAreaType::local->value,
                            ])),

                        TextEntry::make('activity_cities.name')
                            ->label(__('general.city'))
                            ->visible(fn (callable $get) => \in_array($get('area'), [
                                OrganisationAreaType::local->value,
                            ])),
                    ]),

                Section::make(__('organisation.section.branches'))
                    ->schema([
                        IconEntry::make('has_branches')
                            ->label(__('organisation.field.has_branches')),

                        RepeatableEntry::make('branches')
                            ->label(__('organisation.field.branches'))
                            ->columns()
                            ->schema([
                                LocationEntry::make(),

                                TextEntry::make('address')
                                    ->label(__('organisation.field.address'))
                                    ->columnSpanFull(),

                                TextEntry::make('first_name')
                                    ->label(__('organisation.field.contact_person_first_name')),

                                TextEntry::make('last_name')
                                    ->label(__('organisation.field.contact_person_last_name')),

                                TextEntry::make('phone')
                                    ->label(__('organisation.field.phone')),

                                TextEntry::make('email')
                                    ->label(__('organisation.field.email')),

                            ])
                            ->hidden(fn (callable $get) => ! $get('has_branches')),
                    ])
                    ->label(__('organisation.field.resources')),

                Section::make(__('organisation.section.other_information'))
                    ->schema([
                        IconEntry::make('social_services_accreditation')
                            ->label(__('organisation.field.social_services_accreditation'))
                            ->boolean(),
                    ]),
            ]);
    }
}
