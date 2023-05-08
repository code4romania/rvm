<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasLocation;
use App\Models\Resource\Category;
use App\Models\Resource\Subcategory;
use App\Models\Resource\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;
    use HasLocation;

    protected $fillable = [
        'name',
        'category_id',
        'subcategory_id',
        'type_id',
        'attributes',
        'city_id',
        'county_id',
        'organisation_id',
        'contact',
        'other_type',
        'observation',
    ];

    protected $casts = [
        'attributes' => 'array',
        'contact' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategoy(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function type(): ?BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
