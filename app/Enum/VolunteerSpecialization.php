<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use App\Concerns\Enums\HasLabel;

enum VolunteerSpecialization: string
{
    use Arrayable;
    use Comparable;
    use HasLabel;

    case first_aid = 'first_aid';
    case search_rescue = 'search_rescue';
    case stretcher_bearer = 'stretcher_bearer';
    case cook = 'cook';
    case social_worker = 'social_worker';
    case mhpss = 'mhpss';
    case translator = 'translator';

    protected function labelKeyPrefix(): ?string
    {
        return 'volunteer.specialization';
    }
}
