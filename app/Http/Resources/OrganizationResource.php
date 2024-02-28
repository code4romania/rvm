<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'expertises' => IdAndNameResource::collection($this->expertises),
            'risk_categories' => IdAndNameResource::collection($this->riskCategories),
            'resource_types' => IdAndNameResource::collection($this->resourceTypes),
            'area' => $this->area,
            'county' => $this->county->name,
            'activity_counties' => IdAndNameResource::collection($this->activityCounties),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'volunteers_count' => $this->volunteers_count,
        ];
    }
}
