<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResourceResource\Pages;

use App\Filament\Resources\ResourceResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewResource extends ViewRecord
{
    protected static string $resource = ResourceResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getActions(): array
    {
        return [
            EditAction::make(),

            DeleteAction::make(),
        ];
    }
}
