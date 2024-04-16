<?php

namespace Tests\Traits;

use App\Models\User;

trait ActingAsPlatformAdmin
{
    public function getUser(): User
    {
        return User::factory()
            ->platformAdmin()
            ->create();
    }

    public function actingAsPlatformAdmin(): void
    {
        $this->actingAs($this->getUser());
    }
}
