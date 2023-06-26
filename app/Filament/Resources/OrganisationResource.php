<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationType;
use App\Filament\Resources\OrganisationResource\Pages;
use App\Filament\Resources\OrganisationResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\InterventionsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\VolunteersRelationManager;
use App\Models\County;
use App\Models\Organisation;
use Closure;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Str;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
//        debug(Organisation::find(1)->expertises);
        return $form
            ->schema([
                Tabs::make('Heading')
                    ->tabs([
                        Tabs\Tab::make(__('organisation.section.general_data'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('organisation.field.name'))
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $set('alias', Str::slug($state));
                                    })
                                    ->required(),
                                TextInput::make('alias')
                                    ->label(__('organisation.field.alias'))
                                    ->nullable()
                                    ->required(),
                                Radio::make('type')
                                    ->label(__('organisation.field.type'))
                                    ->required()
                                    ->options(OrganisationType::options())
                                    ->inline(),
                                Select::make('year')
                                    ->label(__('organisation.field.year'))
                                    ->placeholder(__('organisation.field.choose'))
                                    ->options(range(today()->year, 1950))
                                    ->required(),
                                Select::make('county_id')
                                    ->label(__('general.county'))
                                    ->options(County::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                                Select::make('city_id')
                                    ->label(__('general.city'))
                                    ->required()
                                    ->options(
                                        fn (callable $get) => County::find($get('county_id'))
                                            ?->cities
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->reactive(),
                                TextInput::make('email')
                                    ->label(__('organisation.field.email_organisation'))
                                    ->email()
                                    ->required(),
                                TextInput::make('phone')
                                    ->label(__('organisation.field.phone_organisation'))
                                    ->required(),
                                TextInput::make('vat')
                                    ->label(__('organisation.field.vat'))
                                    ->required(),
                                TextInput::make('no_registration')
                                    ->label(__('organisation.field.no_registration'))
                                    ->required(),
                                Textarea::make('address')
                                    ->label(__('organisation.field.address'))
                                    ->maxLength(200)
                                    ->columnSpanFull()
                                    ->required(),
                                Textarea::make('description')
                                    ->label(__('organisation.field.short_description'))
                                    ->maxLength(700)
                                    ->helperText(__('organisation.help.description'))
                                    ->columnSpanFull()
                                    ->required(),
                                SpatieMediaLibraryFileUpload::make('logo')->conversion('thumb'),
                                Section::make(__('organisation.field.contact_person'))
                                    ->schema([
                                        TextInput::make('contact_person.name')
                                            ->label(__('organisation.field.contact_person_name'))
                                            ->required(),
                                        TextInput::make('contact_person.role')
                                            ->label(__('organisation.field.role'))
                                            ->required(),
                                        TextInput::make('contact_person.email')
                                            ->label(__('organisation.field.email'))
                                            ->email()
                                            ->required(),
                                        TextInput::make('contact_person.phone')
                                            ->label(__('organisation.field.phone'))
                                            ->required(),

                                    ])
                                    ->columns(2),
                                Section::make(__('organisation.field.other_information'))
                                    ->schema([
                                        TextInput::make('other_information.website')
                                            ->label(__('organisation.field.website'))
                                            ->required(),
                                        TextInput::make('other_information.facebook')
                                            ->label(__('organisation.field.facebook'))
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ])->columns(2),
                        Tabs\Tab::make(__('organisation.section.activity'))
                            ->schema([
                                Select::make('expertises')
                                    ->multiple()
                                    ->helperText(__('general.help.multi_select'))
                                    ->relationship('expertises', 'name')
                                    ->preload()
                                    ->label(__('organisation.field.expertises')),
                                Select::make('risk_category')
                                    ->multiple()
                                    ->helperText(__('general.help.multi_select'))
                                    ->relationship('riskCategories', 'name')
                                    ->preload()
                                    ->label(__('organisation.field.risk_category')),
                                Section::make(__('organisation.section.area_of_activity'))
                                    ->schema([
                                        Radio::make('type_of_area')
                                            ->label(__('organisation.field.type_of_area'))
                                            ->options(OrganisationAreaType::options())
                                            ->reactive()
                                            ->required(),
                                        Repeater::make('areas_of_activity')
                                            ->label(__('organisation.field.area_of_activity.areas'))
                                            ->hidden(function (callable $get) {
                                                return $get('type_of_area') !== OrganisationAreaType::local->value;
                                            })
                                            ->schema([
                                                Select::make('county_id')
                                                    ->label(__('general.county'))
                                                    ->options(County::pluck('name', 'id'))
                                                    ->required()
                                                    ->reactive()
                                                    ->searchable()
                                                    ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                                                Select::make('city_id')
                                                    ->label(__('general.city'))
                                                    ->required()
                                                    ->options(
                                                        fn (callable $get) => County::find($get('county_id'))
                                                            ?->cities
                                                            ->pluck('name', 'id')
                                                    )
                                                    ->searchable()
                                                    ->reactive(),
                                            ])
                                            //TODO add default
                                            ->defaultItems(2)
                                            ->createItemButtonLabel(__('organisation.field.area_of_activity.add_area'))
                                            ->helperText(__('organisation.field.area_of_activity.help_text'))
                                            ->required(),
                                    ]),
                                Section::make(__('organisation.section.resource'))
                                    ->schema([
                                        Select::make('resource_types')
                                            ->multiple()
                                            ->helperText(__('general.help.multi_select'))
                                            ->relationship('resourceTypes', 'name')
                                            ->preload()
                                            ->label(__('organisation.field.resource_types')),
                                    ]),

                                Section::make(__('organisation.section.branches'))
                                    ->schema([
                                        Toggle::make('has_branches')
                                            ->required()
                                            ->label(__('organisation.field.has_branches'))
                                            ->reactive(),
                                        Repeater::make('branches')
                                            ->label(__('organisation.field.branches'))
                                            ->relationship('branches')
                                            ->schema([
                                                TextInput::make('contact_person_name')
                                                    ->label(__('organisation.field.contact_person_name'))
                                                    ->required(),
                                                TextInput::make('phone')
                                                    ->label(__('organisation.field.phone'))
                                                    ->required(),
                                                TextInput::make('email')
                                                    ->label(__('organisation.field.email'))
                                                    ->email()
                                                    ->required(),
                                                Select::make('county_id')
                                                    ->label(__('general.county'))
                                                    ->options(County::pluck('name', 'id'))
                                                    ->required()
                                                    ->reactive()
                                                    ->searchable()
                                                    ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                                                Select::make('city_id')
                                                    ->label(__('general.city'))
                                                    ->required()
                                                    ->options(
                                                        fn (callable $get) => County::find($get('county_id'))
                                                            ?->cities
                                                            ->pluck('name', 'id')
                                                    )
                                                    ->searchable()
                                                    ->reactive(),

                                            ])
                                            ->createItemButtonLabel(__('organisation.field.branch.add_area'))
                                            ->helperText(__('organisation.field.branch.help_text'))
                                            ->hidden(function (callable $get) {
                                                return ! $get('has_branches');
                                            }),
                                    ])
                                    ->label(__('organisation.field.resources')),
                                Section::make(__('organisation.section.other_information'))
                                    ->schema([
                                        Toggle::make('social_services_accreditation')
                                            ->required()
                                            ->label(__('organisation.field.social_services_accreditation'))
                                            ->reactive(),
                                    ]),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('organisation.field.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('organisation.field.type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->options([
                        'heroicon-o-x-circle',
                        'heroicon-o-x' => 'inactive',
                        'heroicon-o-check' => 'active',

                    ])
                    ->colors([
                        'secondary',
                        'danger' => 'inactive',
                        'success' => 'active',
                    ])
                    ->label(__('organisation.field.status'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('county.name')
                    ->label(__('general.county'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('general.updated_at'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('county')
                    ->multiple()
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),
                SelectFilter::make('type')
                    ->multiple()
                    ->label(__('organisation.field.type'))
                    ->options(OrganisationType::options()),
                SelectFilter::make('expertises')
                    ->multiple()
                    ->relationship('expertises', 'name')
                    ->label(__('organisation.field.expertises')),
                SelectFilter::make('riskCategories')
                    ->multiple()
                    ->relationship('riskCategories', 'name')
                    ->label(__('organisation.field.risk_category')),

                SelectFilter::make('resourceTypes')
                    ->multiple()
                    ->relationship('resourceTypes', 'name')
                    ->label(__('organisation.field.resource_types')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->filtersLayout(Layout::AboveContent)
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            VolunteersRelationManager::class,
            ResourcesRelationManager::class,
            InterventionsRelationManager::class,
            UsersRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisations::route('/'),
            'create' => Pages\CreateOrganisation::route('/create'),
            'edit' => Pages\EditOrganisation::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [

        ];
    }
}
