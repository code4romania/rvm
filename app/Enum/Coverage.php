<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum Coverage: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case national = 'national';
    case local = 'local';

    protected function labelKeyPrefix(): ?string
    {
        return 'resource.attributes.coverage';
    }
}
