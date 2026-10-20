<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;

class PersonalInfo extends BasePersonalInfo
{
    public array $only = ['first_name', 'last_name', 'email'];

    protected function getProfileFormComponents(): array
    {
        return [
            $this->getFirstNameComponent(),
            $this->getLastNameComponent(),
            $this->getEmailComponent(),
        ];
    }

    protected function getFirstNameComponent(): TextInput
    {
        return TextInput::make('first_name')
            ->label(__('user.field.first_name'))
            ->required();
    }

    protected function getLastNameComponent(): TextInput
    {
        return TextInput::make('last_name')
            ->label(__('user.field.last_name'))
            ->required();
    }
}
