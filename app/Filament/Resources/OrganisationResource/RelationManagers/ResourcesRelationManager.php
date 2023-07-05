<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Filament\Forms\FieldGroups;
use App\Filament\Resources\ResourceResource;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    protected static ?string $recordTitleAttribute = 'name';

    protected static function getModelLabel(): string
    {
        return ResourceResource::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return ResourceResource::getPluralModelLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('resource.fields.name'))
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'name', fn (Builder $query) => $query->orderBy('id'))
                    ->label(__('resource.fields.category'))
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $set) {
                        $set('subcategory_id', null);
                        $set('types', null);
                        $set('properties', []);
                    }),

                Select::make('subcategory_id')
                    ->label(__('resource.fields.subcategory'))
                    ->options(
                        function (callable $get, callable $set) {
                            $set('types', null);

                            if (! $get('category_id')) {
                                return;
                            }

                            return Subcategory::query()
                                ->inCategory($get('category_id'))
                                ->pluck('name', 'id');
                        }
                    )
                    ->disabled(fn (callable $get) => $get('category_id') === null)
                    ->reactive()
                    ->required(),

                // The logic that toggles the visibility of the field groups
                // is included in the abstract FieldGroup class.
                FieldGroups\AircraftFieldGroup::make(),
                FieldGroups\BoatFieldGroup::make(),
                FieldGroups\BroadcastFieldGroup::make(),
                FieldGroups\RadioFieldGroup::make(),
                FieldGroups\RescueDogFieldGroup::make(),
                FieldGroups\TentFieldGroup::make(),
                FieldGroups\TrailerFieldGroup::make(),
                FieldGroups\TvFieldGroup::make(),
                FieldGroups\VehicleFieldGroup::make(),

                Section::make(__('resource.fields.contact'))
                    ->columns()
                    ->schema([
                        TextInput::make('contact_name')
                            ->label(__('resource.fields.contact'))
                            ->required(),

                        TextInput::make('contact_phone')
                            ->label(__('resource.fields.contact_phone'))
                            ->required(),
                    ]),

                Textarea::make('comments')
                    ->label(__('resource.fields.comments'))
                    ->columnSpanFull(),

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

                TextColumn::make('category.name')
                    ->label(__('resource.fields.category'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('subcategory.name')
                    ->label(__('resource.fields.subcategory'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('county.name')
                    ->label(__('general.county'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('resource.fields.category'))
                    ->relationship('category', 'name'),

                SelectFilter::make('subcategory')
                    ->relationship('subcategory', 'name')
                    ->label(__('resource.fields.subcategory')),

                SelectFilter::make('type')
                    ->label(__('resource.fields.type'))
                    ->relationship('types', 'name'),

                SelectFilter::make('county')
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

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
            ])
            ->defaultSort('id', 'desc');
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
