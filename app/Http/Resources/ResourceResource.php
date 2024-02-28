<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
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
            'county' => $this->county->name,
            'organisation' => IdAndNameResource::make($this->organisation),
            'category' => IdAndNameResource::make($this->category),
            'subcategory' => IdAndNameResource::make($this->subcategory),
            'types' => IdAndNameResource::collection($this->types),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
