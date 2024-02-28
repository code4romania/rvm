<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organisation;

class OrganizationController extends Controller
{
    public function __invoke()
    {
        return OrganizationResource::collection(
            Organisation::query()
                ->withoutEagerLoads(['city'])
                ->select(['id', 'name', 'county_id'])
                ->with([
                    'riskCategories',
                    'activityCounties',
                    'expertises',
                    'resourceTypes',
                ])
                ->with('volunteers')
                ->get()
        );
    }
}
