<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\Scopes\VisibleToCurrenUserScope;

trait LimitsVisibility
{
    public static function bootLimitsVisibility(): void
    {
        static::addGlobalScope(new VisibleToCurrenUserScope);
    }
}
