<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Illuminate\Validation\ValidationException;
use JeffGreco13\FilamentBreezy\Http\Livewire\Auth\Login as BaseLogin;

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
