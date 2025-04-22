<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $coverPhoto = $this->getFirstMedia('cover_photos');

        return [
            'id' => $this->id,
            'organisation' => [
                'id' => $this->organisation->id ?? null,
                'name' => $this->organisation->name ?? null,
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
