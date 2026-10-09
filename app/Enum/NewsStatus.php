<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum NewsStatus: string implements HasLabel, HasColor
{
    use Arrayable;
    use Comparable;

    case published = 'published';
    case archived = 'archived';
    case drafted = 'drafted';

    protected function labelKeyPrefix(): ?string
    {
        return 'news.status';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::published => __('news.status.published'),
            self::archived => __('news.status.archived'),
            self::drafted => __('news.status.drafted'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::drafted => 'secondary',
            self::archived => 'warning',
            self::published => 'success',
        };
    }
}
