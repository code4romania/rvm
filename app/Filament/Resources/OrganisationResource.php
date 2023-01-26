<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\OrganisationType;
use App\Filament\Resources\OrganisationResource\Pages;
use App\Filament\Resources\OrganisationResource\RelationManagers\VolunteersRelationManager;
use App\Models\Expertise;
use App\Models\Organisation;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
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
                                Textarea::make('short_description')
                                    ->label(__('organisation.field.short_description'))
                                    ->maxLength(200)
                                    ->helperText(__('organisation.help.short_description'))
                                    ->columnSpanFull()
                                    ->required(),
                                Textarea::make('description')
                                    ->label(__('organisation.field.description'))
                                    ->maxLength(700)
                                    ->helperText(__('organisation.help.description'))
                                    ->columnSpanFull()
                                    ->required(),
                                //TODO fix file upload
                                //                                FileUpload::make('logo')
                                //                                    ->label(__('organisation.field.logo'))
                                //                                    ->helperText(__('organisation.help.logo'))
                                //                                    ->inlineLabel()
                                //                                    ->columnSpanFull(),
                                Section::make(__('organisation.field.contact_person'))
                                    ->schema([
                                        TextInput::make('contact_person.name')
                                            ->label(__('organisation.field.contact_person_name'))
                                            ->required(),
                                        Placeholder::make('empty')->disableLabel(),
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
                                Select::make('expenses')
                                    ->multiple()
                                    ->options(Expertise::query()->pluck('name', 'id')),
                                //                                    ->loadStateFromRelationshipsUsing(fn($record)=>$record->expertises)

                            ]),
                        Tabs\Tab::make('Label 3')
                            ->schema([
                                //                             RelationM
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ong')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inregistrata')
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('county')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VolunteersRelationManager::class,
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
