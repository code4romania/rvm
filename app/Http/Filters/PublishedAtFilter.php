<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Carbon;

class PublishedAtFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        try {
            $date = Carbon::parse($value);
        } catch (\Exception $e) {
            return $query;
        }

        return $query->whereDate('published_at', $date);
    }
}