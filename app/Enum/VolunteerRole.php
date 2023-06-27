<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum VolunteerRole: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case volunteer = 'volunteer';
    case volunteerCoordinator = 'volunteer_coordinator';

    protected function labelKeyPrefix(): ?string
    {
        return 'volunteer.fields.types';
    }
}
