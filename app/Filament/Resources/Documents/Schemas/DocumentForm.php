<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documents\Schemas;

use App\Enum\DocumentType;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns(2)
                    ->schema([
                        Grid::make()
                            ->columnSpanFull()
                            ->schema([
                                Select::make('organisation_id')
                                    ->label(__('document.field.organisation'))
                                    ->relationship('organisation', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        TextInput::make('name')
                            ->label(__('document.field.name'))
                            ->placeholder(__('document.placeholder.name'))
                            ->maxLength(255)
                            ->required(),

                        Select::make('type')
                            ->label(__('document.field.type'))
                            ->options(DocumentType::class)
                            ->enum(DocumentType::class)
                            ->live()
                            ->required(),

                        Grid::make()
                            ->visible(fn (Get $get) => DocumentType::protocol->is($get('type')))
                            ->schema([
                                DatePicker::make('signed_at')
                                    ->label(__('document.field.signed_at'))
                                    ->required(),

                                Group::make()
                                    ->schema([
                                        DatePicker::make('expires_at')
                                            ->label(__('document.field.expires_at'))
                                            ->after('signed_at')
                                            ->required(fn (Get $get) => ! $get('never_expires'))
                                            ->readOnly(fn (Get $get) => (bool) $get('never_expires'))
                                            ->afterStateHydrated(function (Set $set, $state) {
                                                if (blank($state)) {
                                                    $set('never_expires', true);
                                                }
                                            }),

                                        Checkbox::make('never_expires')
                                            ->label(__('document.field.never_expires'))
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                if ($state === true) {
                                                    $set('expires_at', null);
                                                }
                                            })
                                            ->live(),
                                    ]),
                            ]),
                    ]),

                Section::make(__('document.field.document'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('document')
                            ->downloadable()
                            ->hiddenLabel()
                            ->preserveFilenames()
                            ->visibility('private')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
