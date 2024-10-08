<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getActions(): array
    {
        return [
            'edit' => Actions\EditAction::make(),
            'delete' => Actions\DeleteAction::make(),
        ];
    }
}
