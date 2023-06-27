<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum OrganisationAreaType: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case local = 'local';
    case regional = 'regional';
    case national = 'national';
    case international = 'international';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.field.area_of_activity.types';
    }
}
