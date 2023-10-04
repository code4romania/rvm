<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use JeffGreco13\FilamentBreezy\Pages\MyProfile;

class Settings extends MyProfile
{
    protected static ?string $slug = 'settings';

    protected function getTitle(): string
    {
        return __('auth.settings');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => $this->getTitle(),
        ];
    }

    protected function getUpdateProfileFormSchema(): array
    {
        return [
            TextInput::make('first_name')
                ->required()
                ->label(__('user.field.first_name')),

            TextInput::make('last_name')
                ->required()
                ->label(__('user.field.last_name')),

            TextInput::make($this->loginColumn)
                ->required()
                ->email(fn () => $this->loginColumn === 'email')
                ->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                ->label(__('user.field.email')),
        ];
    }
}
