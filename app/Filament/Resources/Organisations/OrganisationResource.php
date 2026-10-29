<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations;

use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationType;
use App\Filament\Resources\Organisations\Pages\EditOrganisation;
use App\Filament\Resources\Organisations\Pages\ListOrganisations;
use App\Filament\Resources\Organisations\Pages\ViewOrganisation;
use App\Filament\Resources\Organisations\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Organisations\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\Organisations\RelationManagers\UsersRelationManager;
use App\Filament\Resources\Organisations\RelationManagers\VolunteersRelationManager;
use App\Filament\Resources\Organisations\Schemas\OrganisationForm;
use App\Filament\Resources\Organisations\Schemas\OrganisationInfolist;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Organisation;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('organisation.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('organisation.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return OrganisationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganisationInfolist::configure($schema);
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
                    ->formatStateUsing(fn ($record) => $record->type->getLabel())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('county.name')
                    ->label(__('organisation.field.hq'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('area')
                    ->label(__('organisation.section.area_of_activity'))
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
            'index' => ListOrganisations::route('/'),
            'view' => ViewOrganisation::route('/{record}'),
            'edit' => EditOrganisation::route('/{record}/edit'),
        ];
    }
}
