<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\UserRole;
use App\Events\OrganisationCreated;

class CreateFirstOrganisationUser
{
    /**
     * Handle the event.
     */
    public function handle(OrganisationCreated $event): void
    {
        $event->organisation->users()->create([
            'email' => $event->organisation->email,
            'role' => UserRole::ORG_ADMIN,
        ]);
    }
}
