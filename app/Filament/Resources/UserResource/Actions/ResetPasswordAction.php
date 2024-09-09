<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ResetPasswordAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'reset_password';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('user.actions.reset_password'));
        $this->outlined();
        $this->action(function (User $record) {
            $key = $this->getRateLimiterKey($record);
            $maxAttempts = 1;

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                Notification::make()
                    ->title(__('general.warnings.reset_password_too_many_attempts'))
                    ->danger()
                    ->send();

                return;
            }

            RateLimiter::increment($key, 3600);

            $response = Password::broker(config('filament-breezy.reset_broker', config('auth.defaults.passwords')))->sendResetLink(['email' => $record->email]);
            if ($response == Password::RESET_LINK_SENT) {
                Notification::make()->title(__('filament-breezy::default.reset_password.notification_success'))->success()->send();
            } else {
                Notification::make()->title(match ($response) {
                    'passwords.throttled' => __('passwords.throttled'),
                    'passwords.user' => __('passwords.user')
                })->danger()->send();
            }
        });
    }

    private function getRateLimiterKey(User $user): string
    {
        return 'reset-password:' . $user->id;
    }
}
