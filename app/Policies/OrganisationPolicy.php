<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;

class OrganisationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isPlatformAdmin()
            || $user->isPlatformCoordinator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organisation $organisation): bool
    {
        return $user->isPlatformAdmin()
            || $user->isPlatformCoordinator()
            || $user->belongsToOrganisation($organisation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPlatformAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin($organisation);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organisation $organisation): bool
    {
        return $user->isPlatformAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organisation $organisation): bool
    {
        return $user->isPlatformAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organisation $organisation): bool
    {
        return $user->isPlatformAdmin();
    }
}
