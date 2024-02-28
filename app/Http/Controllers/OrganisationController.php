<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;

class OrganisationController extends Controller
{
    public function __invoke()
    {
        return OrganisationResource::collection(
            Organisation::query()
                ->withoutEagerLoads(['city'])
                ->select([
                    'id',
                    'name',
                    'county_id',
                    'type',
                    'status',
                    'area',
                    'created_at',
                    'updated_at',
                ])
                ->with([
                    'riskCategories',
                    'activityCounties',
                    'expertises',
                    'resourceTypes',
                    'county',
                ])
                ->withCount('volunteers')
                ->get()
        );
    }
}
