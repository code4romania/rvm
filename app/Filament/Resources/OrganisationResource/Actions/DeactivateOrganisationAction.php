<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Actions;

use App\Models\Organisation;
use Filament\Pages\Actions\Action;

class DeactivateOrganisationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'deactivate_organisation';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('secondary');

        $this->action(function (Organisation $record, Action $action) {
            $record->setInactive();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('organisation.action.change_status.active.button'));

        $this->modalHeading(__('organisation.action.change_status.active.heading'));
        $this->modalSubheading(__('organisation.action.change_status.active.subheading'));
        $this->modalButton(__('organisation.action.change_status.active.button'));

        $this->successNotificationTitle(__('organisation.action.change_status.active.success'));
    }
}
