<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Illuminate\Validation\ValidationException;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        if (session()->has('organisation_inactive')) {
            throw ValidationException::withMessages([
                'email' => __('auth.organisation_inactive'),
            ]);
        }
    }
}
