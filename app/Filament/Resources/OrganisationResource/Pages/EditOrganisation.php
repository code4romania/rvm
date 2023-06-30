<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\InterventionsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\VolunteersRelationManager;
use Filament\Resources\Pages\EditRecord;

class EditOrganisation extends EditRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getRelationManagers(): array
    {
        return [
            VolunteersRelationManager::class,
            ResourcesRelationManager::class,
            InterventionsRelationManager::class,
            DocumentsRelationManager::class,
            UsersRelationManager::class,
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('view', $this->getRecord());
    }
}
