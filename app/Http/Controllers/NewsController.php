<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Filters\PublishedAfterFilter;
use App\Http\Filters\PublishedAtFilter;
use App\Http\Filters\PublishedBeforeFilter;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    public function __invoke(): JsonResource
    {
        $this->authorize('accessApi');

        return NewsResource::collection(
            QueryBuilder::for(News::class)
                ->allowedFilters([
                    AllowedFilter::custom('published_at', new PublishedAtFilter),
                    AllowedFilter::custom('published_before', new PublishedBeforeFilter),
                    AllowedFilter::custom('published_after', new PublishedAfterFilter),
                ])
                ->with([
                    'media',
                    'organisation' => fn ($query) => $query
                        ->withoutEagerLoads()
                        ->select('id', 'name'),
                ])
                ->where('status', 'published')
                ->defaultSort('-published_at')
                ->paginate(25)
                ->appends(request()->query())
        );
    }
}
