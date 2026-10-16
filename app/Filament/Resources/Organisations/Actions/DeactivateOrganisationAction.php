<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\Actions;

use App\Models\Organisation;
use Filament\Actions\Action;

class DeactivateOrganisationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'deactivate_organisation';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray');

        $this->action(function (Organisation $record, Action $action) {
            $record->setInactive();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('organisation.action.change_status.active.button'));

        $this->modalHeading(__('organisation.action.change_status.active.heading'));
        $this->modalDescription(__('organisation.action.change_status.active.subheading'));
        $this->modalSubmitActionLabel(__('organisation.action.change_status.active.button'));

        $this->successNotificationTitle(__('organisation.action.change_status.active.success'));
    }
}
