<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum NGOType: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case association = 'association';
    case foundation = 'foundation';
    case federation = 'federation';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.field.ngo_types';
    }
}
