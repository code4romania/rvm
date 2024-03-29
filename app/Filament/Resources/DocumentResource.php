<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\DocumentType;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Document;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('document.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('document.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
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

                                Group::make()
                                    ->schema([
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
                    ]),

                Section::make(__('document.field.document'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('document')
                            ->enableDownload()
                            ->disableLabel()
                            ->preserveFilenames()
                            ->visibility('private')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('document.field.name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('document.field.type'))
                    ->enum(DocumentType::options())
                    ->sortable(),

                TextColumn::make('organisation.name')
                    ->label(__('document.field.organisation'))
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('media.file_name')
                    ->label(__('document.field.document'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('signed_at')
                    ->label(__('document.field.signed_at'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('expires_at')
                    ->label(__('document.field.expires_at'))
                    ->date()
                    ->sortable()
                    ->formatStateUsing(function (Document $record, $state) {
                        $fallback = DocumentType::protocol->is($record->type) ?
                            __('document.field.never_expires') :
                            null;

                        return $state?->format(config('tables.date_format')) ?? $fallback;
                    })
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('organisation')
                    ->label(__('document.field.organisation'))
                    ->relationship('organisation', 'name')
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->multiple(),

                SelectFilter::make('type')
                    ->label(__('document.field.type'))
                    ->options(DocumentType::options())
                    ->multiple(),

                DateRangeFilter::make('signed_at')
                    ->label(__('document.field.signed_at'))
                    ->columns(),

                DateRangeFilter::make('expires_at')
                    ->label(__('document.field.expires_at'))
                    ->columns(),
            ])
            ->filtersLayout(Layout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
