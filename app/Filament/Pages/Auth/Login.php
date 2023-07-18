<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Http\Livewire\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
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
