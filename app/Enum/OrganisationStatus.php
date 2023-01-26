<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\ArrayableEnum;

enum OrganisationStatus: string
{
    use ArrayableEnum;

    case active = 'active';
    case inactive = 'inactive';
}
