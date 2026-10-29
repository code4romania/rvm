<?php

declare(strict_types=1);

namespace App\Filament\Resources\Documents;

use App\Enum\DocumentType;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Filament\Resources\Documents\Pages\ViewDocument;
use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\Documents\Schemas\DocumentInfolist;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Document;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('document.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('document.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentInfolist::configure($schema);
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
                    ->sortable(),

                TextColumn::make('organisation.name')
                    ->label(__('document.field.organisation'))
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('media.file_name')
                    ->label(__('document.field.document'))
                    ->toggleable(),

                TextColumn::make('signed_at')
                    ->label(__('document.field.signed_at'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('expires_at')
                    ->label(__('document.field.expires_at'))
                    ->sortable()
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
                    ->label(__('document.field.signed_at')),

                DateRangeFilter::make('expires_at')
                    ->label(__('document.field.expires_at')),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
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
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'view' => ViewDocument::route('/{record}'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
