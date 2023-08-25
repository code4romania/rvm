<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VisibleToCurrenUserScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! auth()->check()) {
            return;
        }

        if (
            auth()->user()->isPlatformAdmin() ||
            auth()->user()->isPlatformCoordinator()
        ) {
            return;
        }

        if (auth()->user()->belongsToOrganisation()) {
            if (! $model instanceof Organisation) {
                $builder->where('organisation_id', auth()->user()->organisation_id);
            }
        }
    }
}
