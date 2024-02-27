<?php

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
        return ['id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'expertises_area' => IdAndNameResource::collection($this->expertises),
            'risc_category' => IdAndNameResource::collection($this->riskCategories),
            'action_type' => IdAndNameResource::collection($this->resourceTypes),
            'activity_area' => $this->area,
            'county' => IdAndNameResource::collection($this->activityCounties),
            'created_at' => $this->created_at->format("Y-m-d H:i:s"),
            'updated_at' => $this->updated_at->format("Y-m-d H:i:s"),
            'volunteers_count' => $this->volunteers->count(),
        ];
    }
}
