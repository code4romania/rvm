<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum NewsStatus: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case published = 'published';
    case archived = 'archived';
    case drafted = 'drafted';

    protected function labelKeyPrefix(): ?string
    {
        return 'news.status';
    }
}
