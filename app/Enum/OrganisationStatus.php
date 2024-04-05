<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum OrganisationStatus: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case active = 'active';
    case inactive = 'inactive';
    case guest = 'guest';
}
