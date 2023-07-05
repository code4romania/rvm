<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceResource\Pages;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\County;
use App\Models\Resource as ResourceModel;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getModelLabel(): string
    {
        return __('resource.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('resource.fields.name'))
                            ->maxLength(255)
                            ->required(),

                        Select::make('organisation_id')
                            ->relationship('organisation', 'name')
                            ->label(__('resource.fields.organisation'))
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label(__('resource.fields.category'))
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (callable $set) {
                                $set('subcategory_id', null);
                                $set('type', null);
                            }),

                        Select::make('subcategory_id')
                            ->label(__('resource.fields.subcategory'))
                            ->options(
                                function (callable $get, callable $set) {
                                    $set('type_id', null);

                                    return Subcategory::query()
                                        ->inCategory($get('category_id'))
                                        ->pluck('name', 'id');
                                }
                            )
                            ->disabled(fn (callable $get) => $get('category_id') === null)
                            ->reactive()
                            ->required(),
                    ]),

                Select::make('type_id')
                    ->label(__('resource.fields.type'))
                    ->options(
                        function (callable $get) {
                            return Subcategory::find($get('subcategory_id'))
                                ?->types
                                ->pluck('name', 'id');
                        }
                    )
                    ->hidden(function (callable $get) {
                        return Subcategory::find($get('subcategory_id'))
                            ?->types->count() == 0;
                    })
                    ->searchable()
                    ->reactive(),
                TextInput::make('type_other')
                    ->label(__('resource.fields.type_other'))
                    ->hidden(function (callable $get) {
                        return Subcategory::find($get('subcategory_id'))
                            ?->types->count() > 0;
                    })
                    ->maxLength(255),

                Section::make('attributes')->heading(__('resource.fields.attributes'))
                    ->schema(function (callable $get) {
                        $attributes = collect(Subcategory::find($get('subcategory_id'))
                            ?->custom_attributes);
                        if ($attributes) {
                            return $attributes->map(
                                function ($attribute) {
                                    switch ($attribute['type']) {
                                        case 'text':
                                            return TextInput::make('attributes.' . $attribute['name'])
                                                ->label(__('resource.attributes.' . $attribute['name']))
                                                ->required()
                                                ->maxLength(255);
                                        case 'checkbox':
                                            return Checkbox::make('attributes.' . $attribute['name'])
                                                ->label(__('resource.attributes.' . $attribute['name']));
                                        case 'select':
                                            return Select::make('attributes.' . $attribute['name'])
                                                ->label(__('resource.attributes.' . $attribute['name']))
                                                ->required()
                                                ->options(
                                                    collect($attribute['options'])
                                                        ->mapWithKeys(fn ($option) => [$option => $option])
                                                );
                                    }
                                }
                            )->toArray();
                        }

                        return [];
                    })->hidden(function (callable $get) {
                        return Subcategory::find($get('subcategory_id'))
                                ?->custom_attributes == null;
                    }),

                Section::make(__('resource.fields.localisation'))
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
                    ]),
                Section::make(__('resource.fields.contact'))
                    ->schema([
                        TextInput::make('contact.person')
                            ->label(__('resource.fields.contact_name'))
                            ->required(),
                        TextInput::make('contact.phone')->label(__('resource.fields.contact_phone')),
                        TextInput::make('contact.email')->label(__('resource.fields.contact_email')),

                    ]),
                Textarea::make('observation')
                    ->label(__('resource.fields.observation'))
                    ->columnSpanFull()->required(),

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

                TextColumn::make('name')
                    ->label(__('resource.fields.name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('organisation.name')
                    ->label(__('resource.fields.organisation'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->label(__('resource.fields.category'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type.name')
                    ->label(__('resource.fields.type'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('county.name')
                    ->label(__('general.county'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('organisation')
                    ->label(__('resource.fields.organisation'))
                    ->relationship('organisation', 'name'),

                SelectFilter::make('category')
                    ->label(__('resource.fields.category'))
                    ->relationship('category', 'name'),

                SelectFilter::make('subcategory')
                    ->relationship('subcategory', 'name')
                    ->label(__('resource.fields.subcategory')),

                SelectFilter::make('type')
                    ->label(__('resource.fields.type'))
                    ->relationship('type', 'name'),

                SelectFilter::make('county')
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

                TernaryFilter::make('attributes')
                    ->label(__('resource.fields.attributes')),
            ])
            ->filtersLayout(Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }
}
