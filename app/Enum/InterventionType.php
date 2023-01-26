<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\ArrayableEnum;

enum InterventionType: string
{
    use ArrayableEnum;

    case prevention = 'prevention';
    case intervention = 'intervention';
    case reconstruction = 'reconstruction';
}
