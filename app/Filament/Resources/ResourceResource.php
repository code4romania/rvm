<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Forms\FieldGroups;
use App\Filament\Resources\ResourceResource\Pages;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Resource as ResourceModel;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Card;
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
use Illuminate\Database\Eloquent\Builder;

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
                            ->preload()
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
                    ]),

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

                Card::make()
                    ->schema([
                        Textarea::make('comments')
                            ->label(__('resource.fields.comments'))
                            ->columnSpanFull(),
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
                    ->relationship('types', 'name'),

                SelectFilter::make('county')
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

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
            'view' => Pages\ViewResource::route('/{record}'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }
}
