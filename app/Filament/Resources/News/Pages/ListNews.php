<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\News\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation');
    }
}
