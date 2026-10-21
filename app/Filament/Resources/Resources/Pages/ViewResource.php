<?php

declare(strict_types=1);

namespace App\Filament\Resources\Resources\Pages;

use App\Filament\Resources\Resources\ResourceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewResource extends ViewRecord
{
    protected static string $resource = ResourceResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            DeleteAction::make(),
        ];
    }
}
