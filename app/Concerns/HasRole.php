<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enum\UserRole;
use App\Models\County;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasRole
{
    public function initializeHasRole()
    {
        $this->casts['role'] = UserRole::class;

        $this->fillable[] = 'role';
    }

    public function scopeRole(Builder $query, array|string|Collection|UserRole $roles): Builder
    {
        return $query->whereIn('role', collect($roles));
    }

    public function hasRole(UserRole | string $role): bool
    {
        return $this->role->is($role);
    }

    public function isPlatformAdmin(): bool
    {
        return $this->hasRole(UserRole::PLATFORM_ADMIN);
    }

    public function isPlatformCoordinator(?County $county = null): bool
    {
        if (! $this->hasRole(UserRole::PLATFORM_COORDINATOR)) {
            return false;
        }

        if ($county === null) {
            return $this->county_id !== null;
        }

        return $this->county_id === $county->id;
    }

    public function isOrgAdmin(?Organisation $organisation = null): bool
    {
        return $this->hasRole(UserRole::ORG_ADMIN) && $this->belongsToOrganisation($organisation);
    }
}
