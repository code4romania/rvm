<?php

declare(strict_types=1);

namespace App\Enum;

use App\Concerns\Enums\Arrayable;
use App\Concerns\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;
enum DocumentType: string implements HasLabel
{
    use Arrayable;
    use Comparable;

    case protocol = 'protocol';
    case contract = 'contract';
    case other = 'other';

    protected function labelKeyPrefix(): ?string
    {
        return 'document.type';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::protocol => __('document.type.protocol'),
            self::contract => __('document.type.contract'),
            self::other => __('document.type.other'),
        };
    }
}
