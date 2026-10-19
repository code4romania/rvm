<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;

class PersonalInfo extends BasePersonalInfo
{
    public array $only = ['first_name', 'last_name', 'email'];

    protected function getProfileFormSchema(): array
    {
        return [Group::make($this->getProfileFormComponents())
            ->columnSpan(3)];
    }

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
            ->required()
            ->label(__('user.field.first_name'));
    }

    protected function getLastNameComponent(): TextInput
    {
        return TextInput::make('last_name')
            ->required()
            ->label(__('user.field.last_name'));
    }
}
