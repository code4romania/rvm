<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\BelongsToOrganisation;
use App\Concerns\HasLocation;
use App\Concerns\LimitsVisibility;
use App\Models\Resource\Category;
use App\Models\Resource\Subcategory;
use App\Models\Resource\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Resource extends Model
{
    use BelongsToOrganisation;
    use HasFactory;
    use HasLocation;
    use LimitsVisibility;

    protected $fillable = [
        'name',
        'category_id',
        'subcategory_id',
        'properties',
        'city_id',
        'county_id',
        'contact_name',
        'contact_phone',
        'other_type',
        'comments',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'resource_has_types');
    }
}
