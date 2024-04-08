<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Actions;

use App\Models\Organisation;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\RateLimiter;

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

        $this->action(function (Organisation $record) {
            $key = $this->getRateLimiterKey($record);
            $maxAttempts = 1;

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                return $this->failure();
            }

            RateLimiter::increment($key, 3600); // 1h

            $record->users->first()->sendWelcomeNotification();
            $this->success();
        });

        $this->requiresConfirmation();

        $this->label(__('organisation.action.resend_invitation.button'));

        $this->modalHeading(__('organisation.action.resend_invitation.heading'));
        $this->modalSubheading(__('organisation.action.resend_invitation.subheading'));
        $this->modalButton(__('organisation.action.resend_invitation.button'));

        $this->successNotificationTitle(__('organisation.action.resend_invitation.success'));

        $this->failureNotification(
            fn (Notification $notification) => $notification
                ->danger()
                ->title(__('organisation.action.resend_invitation.failure_title'))
                ->body(__('organisation.action.resend_invitation.failure_body'))
        );
    }

    private function getRateLimiterKey(Organisation $organisation): string
    {
        return 'resend-invitation:' . $organisation->id;
    }
}
