<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\ArrayableEnum;

enum OrganisationAreaType: string
{
    use ArrayableEnum;

    case local = 'local';
    case regional = 'regional';
    case national = 'national';
    case international = 'international';
}
