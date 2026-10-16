<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\Pages;

use App\Filament\Resources\Organisations\Actions\ActivateOrganisationAction;
use App\Filament\Resources\Organisations\Actions\DeactivateOrganisationAction;
use App\Filament\Resources\Organisations\Actions\ResendInvitationAction;
use App\Filament\Resources\Organisations\OrganisationResource;
use App\Models\Organisation;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganisation extends ViewRecord
{
    protected static string $resource = OrganisationResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActivateOrganisationAction::make()
                ->visible(fn (Organisation $record) => auth()->user()->isPlatformAdmin() && $record->isInactive())
                ->record($this->getRecord()),

            ResendInvitationAction::make()
                ->visible(fn (Organisation $record) => auth()->user()->isPlatformAdmin() && $record->isInvited())
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

    public function getContentTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
