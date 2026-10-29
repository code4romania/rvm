<?php

declare(strict_types=1);

namespace App\Filament\Resources\Resources;

use App\Filament\Filters\ResourceTreeFilter;
use App\Filament\Resources\Resources\Pages\CreateResource;
use App\Filament\Resources\Resources\Pages\EditResource;
use App\Filament\Resources\Resources\Pages\ListResources;
use App\Filament\Resources\Resources\Pages\ViewResource;
use App\Filament\Resources\Resources\Schemas\ResourceForm;
use App\Filament\Resources\Resources\Schemas\ResourceInfolist;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Resource as ResourceModel;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('resource.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return ResourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResourceInfolist::configure($schema);
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
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
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
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->relationship('organisation', 'name'),

                SelectFilter::make('county')
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

                ResourceTreeFilter::make('cat')
                    ->columns(3)
                    ->columnSpan(3), //remove this
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResources::route('/'),
            'create' => CreateResource::route('/create'),
            'view' => ViewResource::route('/{record}'),
            'edit' => EditResource::route('/{record}/edit'),
        ];
    }
}
