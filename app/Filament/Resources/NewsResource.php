<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\NewsStatus;
use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('news.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('news.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(schema: [
                Card::make()
                    ->columns(1)
                    ->schema([

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
                            ->enableOpen()
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
                            ->enableOpen()
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
                    ->collection('cover_photos')
                    ->conversion('thumb')
                    ->extraImgAttributes([
                        'class' => 'object-contain',
                    ])
                    ->width(80)
                    ->height(40)
                    ->visibility('private')
                    ->toggleable(),

                TextColumn::make('title')
                    ->label(__('news.field.title'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('media_count')
                    ->label(__('news.field.media_files_count'))
                    ->counts([
                        'media' => fn (Builder $query) => $query->where('collection_name', 'media_files'),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label(__('news.field.status'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => NewsStatus::drafted->value,
                        'warning' => NewsStatus::archived->value,
                        'success' => NewsStatus::published->value,
                    ])
                    ->enum(NewsStatus::options()),

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
            ->filtersLayout(Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'view' => Pages\ViewNews::route('/{record}'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
