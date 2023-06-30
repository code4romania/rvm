<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use App\Models\Organisation;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganisation extends ViewRecord
{
    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        $status = $this->getRecord()->status?->value;

        return [
            Action::make('change_status')
                ->record($this->getRecord())
                ->action(function (Organisation $record, Action $action) {
                    $record->toggleStatus();
                    $action->success();
                })
                ->requiresConfirmation()
                ->label(__('organisation.action.change_status.' . $status . '.button'))
                ->successNotificationTitle(__('organisation.action.change_status.' . $status . '.success'))
                ->modalHeading(__('organisation.action.change_status.' . $status . '.heading'))
                ->modalSubheading(__('organisation.action.change_status.' . $status . '.subheading'))
                ->modalButton(__('organisation.action.change_status.' . $status . '.button'))
                ->color('secondary'),

            EditAction::make(),

            DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }
}
