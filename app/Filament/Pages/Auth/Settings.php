<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile;

class Settings extends EditProfile
{
    protected static ?string $slug = 'settings';

    public function getTitle(): string
    {
        return __('auth.settings');
    }

    public function getBreadcrumbs(): array
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

    protected function getCreateApiTokenFormSchema(): array
    {
        return [
            TextInput::make('token_name')
                ->label(__('filament-breezy::default.fields.token_name'))
                ->required(),

            Hidden::make('abilities'),
        ];
    }
}
