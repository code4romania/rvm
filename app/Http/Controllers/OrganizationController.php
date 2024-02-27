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
                ->with('riskCategories')
                ->with('activityCounties')
                ->with('expertises')
                ->with('resourceTypes')
                ->with('volunteers')
                ->get()
        );
    }
}
