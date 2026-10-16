<?php

declare(strict_types=1);

namespace App\Filament\Resources\News;

use App\Enum\NewsStatus;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Pages\ViewNews;
use App\Models\News;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
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
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns(1)
                    ->schema([

                        DateTimePicker::make('published_at')
                            ->label(__('news.field.published_at'))
                            ->visible(fn (?News $record) => $record?->isPublished())
                            ->columnSpan(1) // reduce width in grid
                            ->extraAttributes(['class' => 'max-w-sm']),

                        Hidden::make('status')
                            ->default(NewsStatus::drafted),

                        Group::make()
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                Select::make('organisation_id')
                                    ->label(__('news.field.organisation'))
                                    ->relationship('organisation', 'name')
                                    ->required(),
                            ]),

                        SpatieMediaLibraryFileUpload::make('cover_photo')
                            ->collection('cover_photos')
                            ->openable()
                            ->disk('s3-public')
                            ->maxFiles(1)
                            ->conversion('thumb')
                            ->visibility('public')
                            ->label(__('news.field.cover_photo'))
                            ->image(),

                        TextInput::make('title')
                            ->label(__('news.field.title'))
                            ->maxLength(200)
                            ->required(),

                        RichEditor::make('body')
                            ->label(__('news.field.body'))
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('media_files')
                            ->collection('media_files')
                            ->openable()
                            ->disk('s3-public')
                            ->panelLayout('grid')
                            ->multiple()
                            ->conversion('thumb')
                            ->visibility('public')
                            ->label(__('news.field.media_files'))
                            ->image(),
                    ]),
            ]);
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
                    ->height(40)
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
