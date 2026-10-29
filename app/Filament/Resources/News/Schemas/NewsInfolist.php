<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Schemas;

use App\Models\News;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
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
                    ->columns(1)
                    ->schema([
                        TextEntry::make('published_at')
                            ->label(__('news.field.published_at'))
                            ->visible(fn (?News $record) => $record?->isPublished())
                            ->columnSpan(1) // reduce width in grid
                            ->extraAttributes(['class' => 'max-w-sm'])
                            ->dateTime(),

                        Group::make()
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('organisation.name')
                                    ->label(__('news.field.organisation')),
                            ]),

                        SpatieMediaLibraryImageEntry::make('cover_photo')
                            ->collection('cover_photos')
                            ->disk('s3-public')
                            ->visibility('public')
                            ->label(__('news.field.cover_photo')),

                        TextEntry::make('title')
                            ->label(__('news.field.title')),

                        TextEntry::make('body')
                            ->label(__('news.field.body'))
                            ->html(),

                        SpatieMediaLibraryImageEntry::make('media_files')
                            ->collection('media_files')
                            ->disk('s3-public')
                            ->visibility('public')
                            ->label(__('news.field.media_files')),
                    ]),
            ]);
    }
}
