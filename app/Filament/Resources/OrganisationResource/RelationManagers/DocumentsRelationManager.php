<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Enum\DocumentType;
use App\Filament\Resources\DocumentResource;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Document;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getResource(): string
    {
        return DocumentResource::class;
    }

    protected static function getModelLabel(): string
    {
        return static::getResource()::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return static::getResource()::getPluralModelLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('document.field.name'))
                    ->placeholder(__('document.placeholder.name'))
                    ->maxLength(255)
                    ->required(),

                Select::make('type')
                    ->label(__('document.field.type'))
                    ->options(DocumentType::options())
                    ->enum(DocumentType::class)
                    ->reactive()
                    ->required(),

                Grid::make()
                    ->visible(fn (callable $get) => DocumentType::protocol->is($get('type')))
                    ->schema([
                        DatePicker::make('signed_at')
                            ->label(__('document.field.signed_at'))
                            ->required(),
                        Group::make()->schema([
                            DatePicker::make('expires_at')
                                ->label(__('document.field.expires_at'))
                                ->after('signed_at')
                                ->required(fn (Closure $get) => ! $get('never_expires'))
                                ->disabled(fn (Closure $get) => (bool) $get('never_expires'))
                                ->afterStateHydrated(function (Closure $set, $state) {
                                    if (blank($state)) {
                                        $set('never_expires', true);
                                    }
                                }),

                            Checkbox::make('never_expires')
                                ->label(__('document.field.never_expires'))
                                ->afterStateUpdated(function (Closure $set, $state) {
                                    if ($state === true) {
                                        $set('expires_at', null);
                                    }
                                })
                                ->reactive(),
                        ]),
                    ]),

                SpatieMediaLibraryFileUpload::make('document')
                    ->enableDownload()
                    ->label(__('document.field.document'))
                    ->preserveFilenames()
                    ->visibility('private')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('document.field.name'))
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('document.field.type'))
                    ->enum(DocumentType::options()),

                IconColumn::make('media.id')
                    ->label(__('document.field.document'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('signed_at')
                    ->label(__('document.field.signed_at'))
                    ->date()
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label(__('document.field.expires_at'))
                    ->date()
                    ->sortable()
                    ->formatStateUsing(function (Document $record, $state) {
                        $fallback = DocumentType::protocol->is($record->type) ?
                            __('document.field.never_expires') :
                            null;

                        return $state?->format(config('tables.date_format')) ?? $fallback;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make(),

                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('id', 'desc');
    }
}
