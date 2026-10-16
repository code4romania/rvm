<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\RelationManagers;

use Filament\Schemas\Schema;
use App\Filament\Forms\FieldGroups\AircraftFieldGroup;
use App\Filament\Forms\FieldGroups\BoatFieldGroup;
use App\Filament\Forms\FieldGroups\BroadcastFieldGroup;
use App\Filament\Forms\FieldGroups\RadioFieldGroup;
use App\Filament\Forms\FieldGroups\RescueDogFieldGroup;
use App\Filament\Forms\FieldGroups\TentFieldGroup;
use App\Filament\Forms\FieldGroups\TrailerFieldGroup;
use App\Filament\Forms\FieldGroups\TvFieldGroup;
use App\Filament\Forms\FieldGroups\VehicleFieldGroup;
use Filament\Schemas\Components\Section;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Filters\ResourceTreeFilter;
use App\Filament\Forms\FieldGroups;
use App\Filament\Resources\Resources\ResourceResource;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getResource(): string
    {
        return ResourceResource::class;
    }

    protected static function getModelLabel(): ?string
    {
        return static::getResource()::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return static::getResource()::getPluralModelLabel();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('resource.fields.name'))
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'name', fn (Builder $query) => $query->orderBy('id'))
                    ->label(__('resource.fields.category'))
                    ->live()
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
                    ->live()
                    ->required(),

                // The logic that toggles the visibility of the field groups
                // is included in the abstract FieldGroup class.
                AircraftFieldGroup::make(),
                BoatFieldGroup::make(),
                BroadcastFieldGroup::make(),
                RadioFieldGroup::make(),
                RescueDogFieldGroup::make(),
                TentFieldGroup::make(),
                TrailerFieldGroup::make(),
                TvFieldGroup::make(),
                VehicleFieldGroup::make(),

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

    public function table(Table $table): Table
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
                SelectFilter::make('county')
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

                ResourceTreeFilter::make('cat')
                    ->columns(3)
                    ->columnSpan(3),

            ])
            ->headerActions([
                ExportAction::make(),

                CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('resource.modal.heading'))
                    ->modalDescription(__('resource.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->defaultSort('id', 'desc');
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }

    protected function getTableFiltersLayout(): ?string
    {
        return FiltersLayout::AboveContent;
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
