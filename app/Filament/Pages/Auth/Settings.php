<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

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
