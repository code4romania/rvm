<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use Filament\Resources\Pages\EditRecord;

class EditOrganisation extends EditRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getRelationManagers(): array
    {
        return [
            //
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

    public function getFormTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
