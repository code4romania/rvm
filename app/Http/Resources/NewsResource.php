<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $coverPhoto = $this->getFirstMedia('cover_photos');
        // Get organisation logo
        $organisationLogo = $this->organisation?->getFirstMedia();

        return [
            'id' => $this->id,
            'organisation' => [
                'id' => $this->organisation->id ?? null,
                'name' => $this->organisation->name ?? null,
                'logo' => $organisationLogo ? [
                    'url' => $organisationLogo->getUrl(),
                    'thumb' => $organisationLogo->getUrl('thumb'),
                ] : null,
            ],
            'title' => $this->title,
            'body' => $this->body,
            'cover_photo' => $coverPhoto ? [
                'id' => $coverPhoto->id,
                'name' => $coverPhoto->name,
                'url' => $coverPhoto->getUrl(),
                'thumb' => $coverPhoto->getUrl('thumb'),
            ] : null,
            'media_files' => $this->getMedia('media_files')->map(fn($media) => [
                'id' => $media->id,
                'name' => $media->name,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
        ];
    }
}
