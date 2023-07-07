<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Volunteer;

class VolunteerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Volunteer $volunteer): bool
    {
        return $user->isPlatformAdmin()
            || $user->belongsToOrganisation($volunteer->organisation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Volunteer $volunteer): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin($volunteer->organisation);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Volunteer $volunteer): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin($volunteer->organisation);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Volunteer $volunteer): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin($volunteer->organisation);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Volunteer $volunteer): bool
    {
        return $user->isPlatformAdmin()
            || $user->isOrgAdmin($volunteer->organisation);
    }
}
