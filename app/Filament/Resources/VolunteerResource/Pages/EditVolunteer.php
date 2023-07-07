<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use Filament\Resources\Pages\EditRecord;

class EditVolunteer extends EditRecord
{
    protected static string $resource = VolunteerResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('view', $this->getRecord());
    }
}
