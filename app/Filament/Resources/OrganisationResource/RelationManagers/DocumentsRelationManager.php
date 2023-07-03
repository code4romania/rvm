<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Enum\DocumentType;
use App\Filament\Resources\DocumentResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
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

    protected static function getModelLabel(): string
    {
        return DocumentResource::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return DocumentResource::getPluralModelLabel();
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

                        DatePicker::make('expires_at')
                            ->label(__('document.field.expires_at'))
                            ->after('signed_at')
                            ->required(),
                    ]),

                SpatieMediaLibraryFileUpload::make('document')
                    ->enableDownload()
                    ->label(__('document.field.document'))
                    ->preserveFilenames()
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
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }
}
