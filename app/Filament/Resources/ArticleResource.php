<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\ArticleStatus;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return __('article.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('article.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(schema: [
                Card::make()
                    ->columns(1)
                    ->schema([

                        Group::make()
                            ->hidden(fn() => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                Select::make('organisation_id')
                                    ->label(__('article.field.organisation'))
                                    ->relationship('organisation', 'name')
                                    ->required(),
                            ]),

                        SpatieMediaLibraryFileUpload::make('cover_photo')
                            ->collection('cover_photos')
                            ->enableOpen()
                            ->maxFiles(1)
                            ->conversion('thumb')
                            ->visibility('public')
                            ->label(__('article.field.cover_photo'))
                            ->image(),

                        TextInput::make('title')
                            ->label(__('article.field.title'))
                            ->maxLength(200)
                            ->required(),

                        RichEditor::make('body')
                            ->label(__('article.field.body'))
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('media_files')
                            ->collection('media_files')
                            ->enableOpen()
                            ->multiple()
                            ->conversion('thumb')
                            ->visibility('public')
                            ->label(__('article.field.media_files'))
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
                    ->label(__('article.field.organisation'))
                    ->hidden(fn() => auth()->user()->belongsToOrganisation())
                    ->sortable()
                    ->toggleable(),


                TextColumn::make('title')
                    ->label(__('article.field.title'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label(__('article.field.status'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => ArticleStatus::drafted->value,
                        'warning' => ArticleStatus::archived->value,
                        'success' => ArticleStatus::published->value,
                    ])
                    ->enum(ArticleStatus::options()),

                TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->formatStateUsing(fn($state) => $state->toDateTimeString())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label(__('general.updated_at'))
                    ->formatStateUsing(fn($state) => $state->toDateTimeString())
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([

                SelectFilter::make('status')
                    ->multiple()
                    ->label(__('article.field.status'))
                    ->options(ArticleStatus::options())
                    ->query(function (Builder $query, array $state) {
                        $values = $state['values'] ?? null;

                        if (blank($values)) {
                            // Don't apply any filtering if no values are selected
                            return $query;
                        }

                        return $query->whereIn('status', $values);
                    }),

            ])
            ->filtersLayout(Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
