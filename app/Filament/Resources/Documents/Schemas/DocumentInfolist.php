<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Enum\DocumentType;
use App\Models\Document;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->label(__('document.field.organisation')),

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
