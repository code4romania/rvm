<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum DocumentType: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case protocol = 'protocol';
    case contract = 'contract';
    case other = 'other';

    protected function labelKeyPrefix(): ?string
    {
        return 'document.type';
    }
}
