<?php

declare(strict_types=1);

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'resource_categories';

    protected $with = ['subcategories'];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }
}
