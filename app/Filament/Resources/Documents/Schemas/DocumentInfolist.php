<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documents\Schemas;

use App\Filament\Resources\Organisations\RelationManagers\DocumentsRelationManager;
use App\Models\Document;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns()
            ->components([
                Section::make()
                    ->heading('')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('organisation.name')
                            ->label(__('document.field.organisation'))

                            ->hiddenOn(DocumentsRelationManager::class),

                        TextEntry::make('name')
                            ->label(__('document.field.name')),

                        TextEntry::make('type')
                            ->label(__('document.field.type')),

                        TextEntry::make('signed_at')
                            ->date()
                            ->label(__('document.field.signed_at')),

                        TextEntry::make('expires_at')
                            ->label(__('document.field.expires_at')),
                    ]),

                Section::make(__('document.field.document'))
                    ->columnSpan(1)
                    ->visible(fn (Document $record) => $record->hasMedia())
                    ->headerActions([
                        Action::make('download')
                            ->label(__('document.field.download'))
                            ->url(fn (Document $record) => $record->getFirstMediaUrl())
                            ->icon('heroicon-o-arrow-down-tray')
                            ->extraAttributes(fn (Document $record) => [
                                'download' => $record->getFirstMedia()?->getDownloadFilename(),
                            ]),
                    ])
                    ->schema([
                        TextEntry::make('document')
                            ->label(fn (Document $record) => $record->getFirstMedia()?->getDownloadFilename()),
                    ]),
            ]);
    }
}
