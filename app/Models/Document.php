<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\LimitsVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Document extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use LimitsVisibility;

    protected $fillable = [
        'name',
        'type',
        'organisation_id',
        'signed_at',
        'expires_at',
    ];

    protected $casts = [
        'signed_at' => 'date',
        'expires_at' => 'date',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
