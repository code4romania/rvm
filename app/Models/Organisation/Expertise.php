<?php

declare(strict_types=1);

namespace App\Models\Organisation;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expertise extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class);
    }
}
