<?php

declare(strict_types=1);

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'resource_subcategories';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
