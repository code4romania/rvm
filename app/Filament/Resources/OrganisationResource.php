<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\NGOType;
use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use App\Filament\Forms\Components\Location;
use App\Filament\Resources\OrganisationResource\Pages;
use App\Filament\Resources\OrganisationResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\VolunteersRelationManager;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Rules\ValidCIF;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('organisation.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('organisation.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Group::make()
                    ->columnSpan(2)
                    ->schema([
                        Section::make(__('organisation.section.organisation_data'))
                            ->columns()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('organisation.field.name'))
                                    ->placeholder(__('organisation.placeholder.name'))
                                    ->maxLength(200)
                                    ->required(),

                                TextInput::make('alias')
                                    ->label(__('organisation.field.alias'))
                                    ->placeholder(__('organisation.placeholder.alias'))
                                    ->maxLength(200)
                                    ->nullable(),

                                Select::make('type')
                                    ->label(__('organisation.field.type'))
                                    ->options(OrganisationType::options())
                                    ->enum(OrganisationType::class)
                                    ->required()
                                    ->reactive(),

                                Select::make('ngo_type')
                                    ->label(__('organisation.field.ngo_type'))
                                    ->visible(fn (callable $get) => OrganisationType::ngo->is($get('type')))
                                    ->options(NGOType::options())
                                    ->enum(NGOType::class)
                                    ->required(),

                                TextInput::make('year')
                                    ->label(__('organisation.field.year'))
                                    ->placeholder('2006')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(today()->year),

                                TextInput::make('cif')
                                    ->label(__('organisation.field.cif'))
                                    ->rule(new ValidCIF),

                                TextInput::make('registration_number')
                                    ->label(__('organisation.field.registration_number'))
                                    ->maxLength(50),

                                TextInput::make('email')
                                    ->label(__('organisation.field.email_organisation'))
                                    ->maxLength(200)
                                    ->email()
                                    ->required(),

                                TextInput::make('phone')
                                    ->label(__('organisation.field.phone_organisation'))
                                    ->maxLength(14)
                                    ->tel()
                                    ->required(),

                                Textarea::make('description')
                                    ->label(__('organisation.field.short_description'))
                                    ->maxLength(1500)
                                    ->rows(2)
                                    ->helperText(__('organisation.help.description'))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('organisation.section.contact_person'))
                            ->columns()
                            ->schema([
                                TextInput::make('contact_person.first_name')
                                    ->label(__('organisation.field.contact_person_first_name'))
                                    ->maxLength(100),

                                TextInput::make('contact_person.last_name')
                                    ->label(__('organisation.field.contact_person_last_name'))
                                    ->maxLength(100),

                                TextInput::make('contact_person.role')
                                    ->label(__('organisation.field.role'))
                                    ->columnSpanFull()
                                    ->maxLength(200),

                                TextInput::make('contact_person.email')
                                    ->label(__('organisation.field.email'))
                                    ->maxLength(200)
                                    ->email(),

                                TextInput::make('contact_person.phone')
                                    ->label(__('organisation.field.phone'))
                                    ->maxLength(14)
                                    ->tel(),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make(__('organisation.field.logo'))
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('logo')
                                    ->enableOpen()
                                    ->disk('s3-public')
                                    ->conversion('thumb')
                                    ->visibility('public')
                                    ->disableLabel()
                                    ->maxFiles(1)
                                    ->image(),
                            ]),

                        Section::make(__('organisation.field.hq'))
                            ->schema([
                                Location::make()
                                    ->columns(1)
                                    ->required(),

                                TextInput::make('address')
                                    ->label(__('organisation.field.address'))
                                    ->maxLength(200)
                                    ->columnSpanFull(),
                            ]),

                        Section::make(__('organisation.field.other_information'))
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('other_information.website')
                                    ->label(__('organisation.field.website'))
                                    ->maxLength(200)
                                    ->url()
                                    ->nullable(),

                                TextInput::make('other_information.facebook')
                                    ->label(__('organisation.field.facebook'))
                                    ->maxLength(200)
                                    ->url()
                                    ->nullable(),
                            ]),
                    ]),

                Section::make(__('organisation.section.contact_person_in_teams'))
                    ->columns()
                    ->schema([
                        TextInput::make('contact_person_in_teams.first_name')
                            ->label(__('organisation.field.contact_person_in_teams_first_name'))
                            ->maxLength(100),

                        TextInput::make('contact_person_in_teams.last_name')
                            ->label(__('organisation.field.contact_person_in_teams_last_name'))
                            ->maxLength(100),

                        TextInput::make('contact_person_in_teams.role')
                            ->label(__('organisation.field.role'))
                            ->columnSpanFull()
                            ->maxLength(200),

                        TextInput::make('contact_person_in_teams.email')
                            ->label(__('organisation.field.email'))
                            ->maxLength(200)
                            ->email(),

                        TextInput::make('contact_person_in_teams.phone')
                            ->label(__('organisation.field.phone'))
                            ->maxLength(14)
                            ->tel(),
                    ]),

                Section::make(__('organisation.section.activity'))
                    ->columns()
                    ->schema([
                        Select::make('expertises')
                            ->relationship('expertises', 'name')
                            ->label(__('organisation.field.expertises'))
                            ->helperText(__('general.help.multi_select'))
                            ->multiple()
                            ->preload(),

                        Select::make('risk_category')
                            ->relationship('riskCategories', 'name')
                            ->label(__('organisation.field.risk_category'))
                            ->helperText(__('general.help.multi_select'))
                            ->multiple()
                            ->preload()
                            ->required(),

                        Select::make('resource_types')
                            ->relationship('resourceTypes', 'name')
                            ->label(__('organisation.field.resource_types'))
                            ->helperText(__('general.help.multi_select'))
                            ->multiple()
                            ->preload()
                            ->columnSpanFull()
                            ->required(),
                    ]),

                Section::make(__('organisation.section.area_of_activity'))
                    ->columns(3)
                    ->schema([
                        Select::make('area')
                            ->label(__('organisation.field.area'))
                            ->options(OrganisationAreaType::options())
                            ->reactive()
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

                        Select::make('activity_counties')
                            ->label(__('general.county'))
                            ->relationship('activityCounties', 'name')
                            ->options(function () {
                                return Cache::driver('array')
                                    ->rememberForever(
                                        'counties',
                                        fn () => County::pluck('name', 'id')
                                    );
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (callable $get) => \in_array($get('area'), [
                                OrganisationAreaType::regional->value,
                                OrganisationAreaType::local->value,
                            ]))
                            ->reactive(),

                        Select::make('activity_cities')
                            ->label(__('general.city'))
                            ->relationship('activityCities', 'name')
                            ->searchable()
                            ->multiple()
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $counties = Arr::wrap($get('activity_counties'));

                                if (empty($counties)) {
                                    return [];
                                }

                                return City::query()
                                    ->whereIn('county_id', $counties)
                                    ->search($search)
                                    ->limit(100)
                                    ->get()
                                    ->pluck('name', 'id');
                            })
                            ->visible(fn (callable $get) => \in_array($get('area'), [
                                OrganisationAreaType::local->value,
                            ]))
                            ->required(),
                    ]),

                Section::make(__('organisation.section.branches'))
                    ->schema([
                        Toggle::make('has_branches')
                            ->label(__('organisation.field.has_branches'))
                            ->reactive(),

                        Repeater::make('branches')
                            ->label(__('organisation.field.branches'))
                            ->relationship('branches')
                            ->minItems(1)
                            ->columns()
                            ->schema([
                                Location::make()
                                    ->required(),

                                TextInput::make('address')
                                    ->label(__('organisation.field.address'))
                                    ->maxLength(200)
                                    ->columnSpanFull()
                                    ->nullable(),

                                TextInput::make('first_name')
                                    ->label(__('organisation.field.contact_person_first_name'))
                                    ->maxLength(100)
                                    ->nullable(),

                                TextInput::make('last_name')
                                    ->label(__('organisation.field.contact_person_last_name'))
                                    ->maxLength(100)
                                    ->nullable(),

                                TextInput::make('phone')
                                    ->label(__('organisation.field.phone'))
                                    ->maxLength(200)
                                    ->nullable(),

                                TextInput::make('email')
                                    ->label(__('organisation.field.email'))
                                    ->maxLength(200)
                                    ->email()
                                    ->nullable(),

                            ])
                            ->createItemButtonLabel(__('organisation.field.branch.add_area'))
                            ->hidden(fn (callable $get) => ! $get('has_branches')),
                    ])
                    ->label(__('organisation.field.resources')),

                Section::make(__('organisation.section.other_information'))
                    ->schema([
                        Toggle::make('social_services_accreditation')
                            ->label(__('organisation.field.social_services_accreditation')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                SpatieMediaLibraryImageColumn::make('logo')
                    ->conversion('thumb')
                    ->extraImgAttributes([
                        'class' => 'object-contain',
                    ])
                    ->width(80)
                    ->height(40)
                    ->visibility('private')
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('organisation.field.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('organisation.field.type'))
                    ->formatStateUsing(fn ($record) => $record->type->label())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => OrganisationStatus::inactive->value,
                        'warning' => OrganisationStatus::invited->value,
                        'success' => OrganisationStatus::active->value,
                    ])
                    ->enum(OrganisationStatus::options()),

                TextColumn::make('county.name')
                    ->label(__('organisation.field.hq'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('area')
                    ->label(__('organisation.section.area_of_activity'))
                    ->enum(OrganisationAreaType::options())
                    ->description(
                        fn (Organisation $record) => $record
                            ->activityCounties
                            ->pluck('name')
                            ->join(', ')
                    )
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->formatStateUsing(fn ($state) => $state->toDateTimeString())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label(__('general.updated_at'))
                    ->formatStateUsing(fn ($state) => $state->toDateTimeString())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('county')
                    ->multiple()
                    ->label(__('organisation.field.hq'))
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

                SelectFilter::make('area')
                    ->label(__('organisation.section.area_of_activity'))
                    ->options(OrganisationAreaType::options())
                    ->multiple(),

                SelectFilter::make('activityCounties')
                    ->relationship('activityCounties', 'name')
                    ->label(__('general.county'))
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['values'])) {
                            return;
                        }

                        $hasAreaBindings = collect($query->getQuery()->wheres)
                            ->filter(fn (array $where) => $where['column'] === 'area')
                            ->isNotEmpty();

                        if (! $hasAreaBindings) {
                            $query
                                ->whereRaw('1 = 1')
                                ->orWhere('area', OrganisationAreaType::national);
                        }
                    }),

            ])
            ->filtersLayout(Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            VolunteersRelationManager::class,
            ResourcesRelationManager::class,
            UsersRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisations::route('/'),
            'view' => Pages\ViewOrganisation::route('/{record}'),
            'edit' => Pages\EditOrganisation::route('/{record}/edit'),
        ];
    }
}
