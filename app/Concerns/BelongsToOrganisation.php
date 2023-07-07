<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganisation
{
    public function initializeBelongsToOrganisation(): void
    {
        $this->fillable[] = 'organisation_id';
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function belongsToOrganisation(?Organisation $organisation = null): bool
    {
        if ($organisation === null) {
            return $this->organisation_id !== null;
        }

        return $this->organisation_id === $organisation->id;
    }
}
