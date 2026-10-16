<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\Pages;

use App\Filament\Resources\Organisations\OrganisationResource;
use Filament\Resources\Pages\EditRecord;

class EditOrganisation extends EditRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    public function getRelationManagers(): array
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
        return static::getResource()::getUrl('view', [$this->getRecord()]);
    }

    public function getContentTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
