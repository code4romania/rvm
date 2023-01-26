<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\ArrayableEnum;

enum OrganisationType: string
{
    use ArrayableEnum;

    case association = 'association';
    case foundation = 'foundation';
    case federation = 'federation';
    case informalGroup = 'informal_group';
}
