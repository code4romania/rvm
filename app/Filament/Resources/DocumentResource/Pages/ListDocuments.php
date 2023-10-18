<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation');
    }
}
