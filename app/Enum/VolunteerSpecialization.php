<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum VolunteerSpecialization: string implements HasLabel
{
    use Arrayable;
    use Comparable;

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

    public function getLabel(): ?string {
        return match ($this) {
            self::first_aid => __('volunteer.specialization.first_aid'),
            self::search_rescue => __('volunteer.specialization.search_rescue'),
            self::stretcher_bearer => __('volunteer.specialization.stretcher_bearer'),
            self::cook => __('volunteer.specialization.cook'),
            self::social_worker => __('volunteer.specialization.social_worker'),
            self::mhpss => __('volunteer.specialization.mhpss'),
            self::translator => __('volunteer.specialization.translator'),
        };
    }
}
