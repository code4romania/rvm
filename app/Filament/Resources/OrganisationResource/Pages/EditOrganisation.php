<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Enum\OrganisationStatus;
use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\InterventionsRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\RelationManagers\VolunteersRelationManager;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganisation extends EditRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        $recordStatus = self::getRecord()->status;

        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('change_status')
                ->action(function () {
                    $record = self::getRecord();
                    $record->status = ($record->status == OrganisationStatus::active->value) ? OrganisationStatus::inactive->value : OrganisationStatus::active->value;
                    $record->save();
                })->label(__('organisation.action.change_status.' . $recordStatus . '.button'))
                ->requiresConfirmation()
                ->modalHeading(__('organisation.action.change_status.' . $recordStatus . '.heading'))
                ->modalSubheading(__('organisation.action.change_status.' . $recordStatus . '.subheading'))
                ->modalButton(__('organisation.action.change_status.' . $recordStatus . '.button')),
        ];
    }

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
}
