<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum VolunteerRole: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case volunteer = 'volunteer';
    case coordinator = 'coordinator';
    case other = 'other';

    protected function labelKeyPrefix(): ?string
    {
        return 'volunteer.role';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::volunteer => __('volunteer.role.volunteer'),
            self::coordinator => __('volunteer.role.coordinator'),
            self::other => __('volunteer.role.other'),
        };
    }
}
