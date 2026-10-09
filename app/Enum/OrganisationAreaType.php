<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum OrganisationAreaType: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case local = 'local';
    case regional = 'regional';
    case national = 'national';
    case international = 'international';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.field.area_of_activity.types';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::local => __('organisation.field.area_of_activity.types.local'),
            self::regional => __('organisation.field.area_of_activity.types.regional'),
            self::national => __('organisation.field.area_of_activity.types.national'),
            self::international => __('organisation.field.area_of_activity.types.international'),
        };
    }
}
