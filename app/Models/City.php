<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\AlphabeticalOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'level',
        'type',
        'name',
        'county_id',
        'parent_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new AlphabeticalOrder);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function getNameWithCountyAttribute(): string
    {
        return "{$this->name}, {$this->county->name}";
    }
}
