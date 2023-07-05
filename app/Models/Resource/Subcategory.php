<?php

declare(strict_types=1);

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'resource_subcategories';

    protected $with = [
        'types',
    ];

    protected $casts = [
        'custom_attributes' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function types(): HasMany
    {
        return $this->hasMany(Type::class);
    }

    public function scopeInCategory(Builder $query, int $category_id): Builder
    {
        return $query->where('category_id', $category_id);
    }
}
