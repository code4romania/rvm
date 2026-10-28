<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/welcome/{user}', App\Livewire\Welcome::class)->name('auth.welcome');
