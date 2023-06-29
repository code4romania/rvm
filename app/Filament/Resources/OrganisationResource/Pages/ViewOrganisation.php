<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganisation extends ViewRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }
}
