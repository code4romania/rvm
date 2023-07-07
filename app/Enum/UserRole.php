<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum UserRole: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case PLATFORM_ADMIN = 'platform_admin';
    case PLATFORM_COORDINATOR = 'platform_coordinator';
    case ORG_ADMIN = 'org_admin';
    case ORG_MEMBER = 'org_member';

    protected function labelKeyPrefix(): ?string
    {
        return 'user.role';
    }
}
