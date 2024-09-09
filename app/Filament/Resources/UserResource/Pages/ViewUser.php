<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Password;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('reset_password')
                ->label('Reset Password')
                ->outlined()
                ->action(function () {
                    $response = Password::broker(config('filament-breezy.reset_broker', config('auth.defaults.passwords')))->sendResetLink(['email' => $this->getRecord()->email]);
                    if ($response == Password::RESET_LINK_SENT) {
                        Notification::make()->title(__('filament-breezy::default.reset_password.notification_success'))->success()->send();

                        $this->hasBeenSent = true;
                    } else {
                        Notification::make()->title(match ($response) {
                            'passwords.throttled' => __('passwords.throttled'),
                            'passwords.user' => __('passwords.user')
                        })->danger()->send();
                    }
                }),

            EditAction::make(),
        ];
    }
}
