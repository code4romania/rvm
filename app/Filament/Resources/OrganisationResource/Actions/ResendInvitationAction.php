<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Actions;

use App\Models\Organisation;
use Filament\Pages\Actions\Action;

class ResendInvitationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'resend_invitation';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('secondary');

        $this->action(function (Organisation $record, Action $action) {
            $record->users->first()->sendWelcomeNotification();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('organisation.action.resend_invitation.button'));

        $this->modalHeading(__('organisation.action.resend_invitation.heading'));
        $this->modalSubheading(__('organisation.action.resend_invitation.subheading'));
        $this->modalButton(__('organisation.action.resend_invitation.button'));

        $this->successNotificationTitle(__('organisation.action.resend_invitation.success'));
    }
}
