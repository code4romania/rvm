<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationController extends Controller
{
    public function __invoke(): JsonResource
    {
        $this->authorize('accessApi');

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
                    'contact_person_in_teams',
                    'description',
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
