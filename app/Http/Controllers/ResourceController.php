<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ResourceResource;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function __invoke()
    {
        return ResourceResource::collection(
            Resource::query()
                ->with([
                    'county',
                    'category',
                    'subcategory',
                    'types',
                    'organisation' => fn ($query) => $query
                        ->withoutEagerLoads()
                        ->select('id', 'name'),
                ])
                ->get()
        );
    }
}
