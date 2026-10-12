<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum NGOType: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case association = 'association';
    case foundation = 'foundation';
    case federation = 'federation';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.field.ngo_types';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::foundation => __('organisation.field.ngo_types.foundation'),
            self::federation => __('organisation.field.ngo_types.federation'),
            self::association => __('organisation.field.ngo_types.association'),
        };
    }
}
