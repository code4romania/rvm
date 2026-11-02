<?php

declare(strict_types=1);

namespace App\Filament\Resources\News;

use App\Enum\NewsStatus;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Pages\ViewNews;
use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Resources\News\Schemas\NewsInfolist;
use App\Models\News;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('news.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('news.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return NewsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('organisation.name')
                    ->label(__('news.field.organisation'))
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->sortable()
                    ->toggleable(),

                SpatieMediaLibraryImageColumn::make('cover_photo')
                    ->label(__('news.field.cover_photo'))
                    ->collection('cover_photos')
                    ->conversion('thumb')
                    ->extraImgAttributes([
                        'class' => 'object-contain',
                    ])
                    ->width(80)
                    ->imageHeight(40)
                    ->visibility('public')
                    ->toggleable(),

                TextColumn::make('title')
                    ->label(__('news.field.title'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label(__('news.field.status'))
                    ->badge()
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('published_at')
                    ->label(__('news.field.published_at'))
                    ->formatStateUsing(fn ($state) => $state?->toDateTimeString() ?? '-')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

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
            ->defaultSort('id', 'desc')
            ->filters([

                SelectFilter::make('status')
                    ->multiple()
                    ->label(__('news.field.status'))
                    ->options(NewsStatus::options())
                    ->query(function (Builder $query, array $state) {
                        $values = $state['values'] ?? null;

                        if (blank($values)) {
                            // Don't apply any filtering if no values are selected
                            return $query;
                        }

                        return $query->whereIn('status', $values);
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'view' => ViewNews::route('/{record}'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }
}
