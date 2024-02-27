<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResourceResource;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function __invoke()
    {
        return ResourceResource::collection(
            Resource::query()
                ->with('county')
                ->with('organisation')
                ->with('category')
                ->with('subcategory')
                ->with('types')
                ->get()
        );
    }

}
