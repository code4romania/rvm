<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enum\NewsStatus;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns(1)
                    ->schema([

                        TextEntry::make('published_at')
                            ->label(__('news.field.published_at'))
                            ->visible(fn (?News $record) => $record?->isPublished())
                            ->columnSpan(1) // reduce width in grid
                            ->extraAttributes(['class' => 'max-w-sm'])
                            ->date(),

                        Group::make()
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('organisation.name')
                                    ->label(__('news.field.organisation')),
                            ]),

                        ImageEntry::make('cover_photo')
                            ->disk('s3-public')
                            ->visibility('public')
                            ->label(__('news.field.cover_photo')),

                        TextEntry::make('title')
                            ->label(__('news.field.title')),

                        TextEntry::make('body')
                            ->label(__('news.field.body')),

                        ImageEntry::make('media_files')
                            ->disk('s3-public')
                            ->stacked()
                            ->visibility('public')
                            ->label(__('news.field.media_files')),
                    ]),
            ]);
    }
}
