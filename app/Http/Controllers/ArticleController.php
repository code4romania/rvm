<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Resource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleController extends Controller
{
    public function __invoke(): JsonResource
    {
        $this->authorize('accessApi');

        return ArticleResource::collection(
            Resource::query()
                ->with([
                    'media',
                    'organisation' => fn($query) => $query
                        ->withoutEagerLoads()
                        ->select('id', 'name'),
                ])
                ->paginate(request()->get('per_page', 10))
        );
    }
}
