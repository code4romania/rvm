<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsController extends Controller
{
    public function __invoke(): JsonResource
    {
        $this->authorize('accessApi');

        return NewsResource::collection(
            News::query()
                ->with([
                    'media',
                    'organisation' => fn(BelongsTo $query) => $query
                        ->withoutEagerLoads()
                        ->select('id', 'name'),
                ])
                ->orderBy('id', 'desc')
                ->paginate(25)
        );
    }
}
