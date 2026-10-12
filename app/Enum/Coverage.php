<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum Coverage: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case national = 'national';
    case local = 'local';

    protected function labelKeyPrefix(): ?string
    {
        return 'resource.attributes.coverage';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::national => __('resource.attributes.coverage.national'),
            self::local => __('resource.attributes.coverage.local'),
        };
    }
}
