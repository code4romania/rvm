<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Actions\ActivateOrganisationAction;
use App\Filament\Resources\OrganisationResource\Actions\DeactivateOrganisationAction;
use App\Filament\Resources\OrganisationResource\Actions\ResendInvitationAction;
use App\Models\Organisation;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganisation extends ViewRecord
{
    protected static string $resource = OrganisationResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getActions(): array
    {
        return [
            ActivateOrganisationAction::make()
                ->visible(fn (Organisation $record) => auth()->user()->isPlatformAdmin() && $record->isInactive())
                ->record($this->getRecord()),

            ResendInvitationAction::make()
                ->visible(fn (Organisation $record) => auth()->user()->isPlatformAdmin() && $record->isGuest())
                ->record($this->getRecord()),

            DeactivateOrganisationAction::make()
                ->visible(fn (Organisation $record) => auth()->user()->isPlatformAdmin() && $record->isActive())
                ->record($this->getRecord()),

            EditAction::make(),

            DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    public function getFormTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
