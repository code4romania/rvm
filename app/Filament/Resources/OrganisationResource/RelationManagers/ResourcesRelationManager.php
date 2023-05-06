<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Models\County;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('category')->options([
                    'adapost' => 'Adăpostire',
                    'transport' => 'Transport',
                    'salvare' => 'Salvare',
                    'telecomunicatii' => 'Telecomunicații',
                    'it_c' => 'IT&C',
                    'other' => 'Altele',
                ])->reactive()
                    ->required(),

                Select::make('subcategory')
                    ->label('Subcategory')
                    ->required()
                    ->options(
                        fn (callable $get) => match ($get('category')) {
                            'adapost' => ['corturi' => 'Corturi', 'rulota' => 'Rulote', 'cazare' => 'Cazare', 'altele' => 'Altele'],
                            'transport' => ['rutier' => 'Rutier', 'maritim' => 'Maritim', 'feroviar' => 'Feroviar', 'aerian' => 'Aerian', 'altele' => 'Altele'],
                            'salvare' => ['caini_utilitari' => 'Câini utilitari', 'Altele'],
                            'telecomunicatii' => ['Radiocomunicații', 'Televiziune', 'Radiodifuziune', 'Altele'],
                            'it_c' => ['Hardware', 'Software', 'Altele'],
                            default => ['Altele'],
                        }
                    )
                    ->hidden(function (callable $get) {
                        return $get('category') === 'altele';
                    })
                    ->searchable()
                    ->reactive(),
                Select::make('resource_type')
                    ->label('Resource type')
                    ->required()
                    ->options(
                        fn (callable $get) => match ($get('subcategory')) {
                            'corturi' => ['Iarna', 'Vara', 'Gonflabil', 'Pe structura metalică', 'Utilat', 'Neutilat', 'Altul'],
                            'rutier' => ['Masina', 'Duba', 'Camion', 'Altele'],
                            'caini_utilitari' => ['Căutare în mediul urban', 'Căutare în mediu natural'],
                            default => ['Altele'],
                        }
                    )
                    ->hidden(function (callable $get) {
                        return $get('category') == 'altele';
                    })
                    ->searchable()
                    ->reactive(),
                // TODO check de ce nu afiseaza inputurile de mai jos
                //// TODO handel on change remove completed
                TextInput::make('resource_type')
                    ->label('Resource type')
                    ->hidden(function (callable $get) {
                        return $get('subcategory') !== 'rulota';
                    })
                    ->maxLength(255),

                TextInput::make('subcategory_other')
                    ->label('Subcategory other')
                    ->hidden(function (callable $get) {
                        return $get('category') !== 'altele';
                    })
                    ->maxLength(255),

                //corturi
                //use getCourturi
                //use getRulota
                //use Transport rutier
                //use Transport Maritim /Aerian
                //use Caini_utilitari
                //use Radiocomunicații
                //use Televiziune / Radiodifuziune

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('has_transport')
                    ->options([
                        'heroicon-o-x-circle',
                        'heroicon-o-x' => 'false',
                        'heroicon-o-check' => 'true',

                    ])
                    ->colors([
                        'secondary',
                        'danger' => 'false',
                        'success' => 'true',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('organisation.modal.heading'))
                    ->modalSubheading(__('organisation.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    private static function getCorturi()
    {
        return [
            TextInput::make('dimension'),
            TextInput::make('capacity'),
            TextInput::make('quantity'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                    Checkbox::make('relocation_resource'),
                    Checkbox::make('has_transport'),
                ]),

            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),

                ]),
            Textarea::make('observation')
                ->required(),
        ];
    }

    private static function getRulote()
    {
        return [
            TextInput::make('dimension'),
            TextInput::make('capacity'),
            TextInput::make('quantity'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),

            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required()];
    }

    private static function getTransportRutier()
    {
        return [
            TextInput::make('capacity'),
            TextInput::make('quantity'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),

            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required(),

        ];
    }

    private static function getMaritim()
    {
        return [
            TextInput::make('capacity'),
            TextInput::make('quantity'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),

            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required(),

        ];
    }

    private static function getCainiUtilitari()
    {
        return [
            TextInput::make('dog_name'),
            TextInput::make('volunteer_name'),
            TextInput::make('volunteer_specialization'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),
            Checkbox::make('has_trailer'),
            Checkbox::make('has_carriage'),
            Checkbox::make('has_transport')
                ->label('Has transport')
                ->required(),

            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required(),
        ];
    }

    public static function getRadiocomunicații()
    {
        return [

            TextInput::make('tech_type'),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),
            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required(),
        ];
    }

    public static function getTeleviziune()
    {
        return [
            Select::make('area')->options(['Nationala', 'Locala']),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label('County')
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),
            Section::make(__('Contact'))
                ->schema([
                    TextInput::make('contact_person'),
                    TextInput::make('contact_phone'),
                    TextInput::make('contact_email'),
                ]),
            Textarea::make('observation')
                ->required(),
        ];
    }
}
