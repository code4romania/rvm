<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum OrganisationStatus: string implements HasLabel, HasColor
{
    use Arrayable;
    use Comparable;

    case active = 'active';
    case inactive = 'inactive';
    case invited = 'invited';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.status';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::active => __('organisation.status.active'),
            self::inactive => __('organisation.status.inactive'),
            self::invited => __('organisation.status.invited'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::inactive => 'secondary',
            self::invited => 'warning',
            self::active => 'success',
        };
    }
}
