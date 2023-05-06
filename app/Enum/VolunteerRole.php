<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\ArrayableEnum;

enum VolunteerRole: string
{
    use ArrayableEnum;

    case volunteer = 'volunteer';
    case volunteerCoordinator = 'volunteer_coordinator';

    protected function translationKeyPrefix(): ?string
    {
        return 'volunteer.fields.types';
    }
}
