<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Actions;

use App\Models\Organisation;
use Filament\Pages\Actions\Action;

class ActivateOrganisationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'activate_organisation';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('secondary');

        $this->action(function (Organisation $record, Action $action) {
            $record->setActive();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('organisation.action.change_status.inactive.button'));

        $this->modalHeading(__('organisation.action.change_status.inactive.heading'));
        $this->modalSubheading(__('organisation.action.change_status.inactive.subheading'));
        $this->modalButton(__('organisation.action.change_status.inactive.button'));

        $this->successNotificationTitle(__('organisation.action.change_status.inactive.success'));
    }
}
