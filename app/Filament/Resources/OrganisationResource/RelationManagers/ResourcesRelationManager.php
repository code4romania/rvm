<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Models\County;
use App\Models\Resource\Category;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

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
                    ->label(__('resource.fields.name'))
                    ->maxLength(255),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->reactive()
                    ->label(__('resource.fields.category'))
                    ->required(),

                Select::make('subcategory_id')
                    ->label('Subcategory')
                    ->required()
                    ->label(__('resource.fields.subcategory'))
                    ->options(
                        function (callable $get, callable $set) {
                            $set('type_id', null);

                            return Category::find($get('category_id'))
                                ?->subcategories
                                ->pluck('name', 'id');
                        }
                    )
                    ->searchable()
                    ->reactive(),

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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category.name')->label(__('resource.fields.category')),
                Tables\Columns\TextColumn::make('subcategory.name')->label(__('resource.fields.subcategory')),
                Tables\Columns\TextColumn::make('type.name')->label(__('resource.fields.type')),
                Tables\Columns\TextColumn::make('county.name')->label(__('general.county')),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label(__('resource.fields.category')),
                SelectFilter::make('subcategory')
                    ->relationship('subcategory', 'name')
                    ->label(__('resource.fields.subcategory')),
                SelectFilter::make('type')
                    ->relationship('type', 'name')
                    ->label(__('resource.fields.type')),
                SelectFilter::make('county')->label(__('general.county'))
                    ->relationship('county', 'name'),
                TernaryFilter::make('attributes')->label(__('resource.fields.attributes')),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('resource.modal.heading'))
                    ->modalSubheading(__('resource.modal.subheading'))
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
    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }
    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }
    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

}
