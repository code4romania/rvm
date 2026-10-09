<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

class Settings extends MyProfilePage
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
}
