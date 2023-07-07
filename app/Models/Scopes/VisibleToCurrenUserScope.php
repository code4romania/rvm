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

        if (auth()->user()->isPlatformAdmin()) {
            return;
        }

        if (auth()->user()->isPlatformCoordinator()) {
            if ($model instanceof Organisation) {
                $builder->where('county_id', auth()->user()->county_id);
            } else {
                $builder->whereHas('organisation');
            }
        }

        if (auth()->user()->belongsToOrganisation()) {
            $builder->whereRelation('organisation', 'id', auth()->user()->organisation_id);
        }
    }
}
