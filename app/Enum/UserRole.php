<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case PLATFORM_ADMIN = 'platform_admin';
    case PLATFORM_COORDINATOR = 'platform_coordinator';
    case ORG_ADMIN = 'org_admin';

    protected function labelKeyPrefix(): ?string
    {
        return 'user.role';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PLATFORM_ADMIN => __('user.role.platform_admin'),
            self::PLATFORM_COORDINATOR => __('user.role.platform_coordinator'),
            self::ORG_ADMIN => __('user.role.org_admin'),
        };
    }
}
