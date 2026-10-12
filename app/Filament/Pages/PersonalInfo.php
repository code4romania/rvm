<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;

class PersonalInfo extends BasePersonalInfo
{
    public array $only = ['first_name', 'last_name', 'email'];

    protected function getProfileFormSchema(): array
    {

        return [
            Grid::make(2)->schema([
                TextInput::make('first_name')
                    ->required()
                    ->label(__('user.field.first_name')),

                TextInput::make('last_name')
                    ->required()
                    ->label(__('user.field.last_name')),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                    ->label(__('user.field.email')),
                ])
        ];
    }
}
