<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum OrganisationType: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case ngo = 'ngo';
    case private = 'private';
    case public = 'public';
    case academic = 'academic';

    protected function labelKeyPrefix(): ?string
    {
        return 'organisation.field.types';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ngo => __('organisation.field.types.ngo'),
            self::private => __('organisation.field.types.private'),
            self::public => __('organisation.field.types.public'),
            self::academic => __('organisation.field.types.academic'),
        };
    }
}
