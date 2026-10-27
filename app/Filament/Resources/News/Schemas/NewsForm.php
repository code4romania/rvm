<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enum\NewsStatus;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
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
}
