<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\BelongsToOrganisation;
use App\Concerns\LimitsVisibility;
use App\Enum\NewsStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Date;

class News extends Model implements HasMedia
{
    use BelongsToOrganisation;
    use HasFactory;
    use InteractsWithMedia;
    use LimitsVisibility;

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, 80, 40)
            ->nonQueued();
    }

    protected $fillable = [
        'title',
        'body',
        'status',
        'published_at'
    ];

    protected $casts = [
        'status' => NewsStatus::class,
        'published_at' => 'datetime',
    ];

    public function scopeWherePublished(Builder $query): Builder
    {
        return $query->where('status', NewsStatus::published);
    }

    public function archive(): bool
    {
        return $this->update([
            'status' => NewsStatus::archived,
        ]);
    }

    public function publish(): bool
    {
        return $this->update([
            'status' => NewsStatus::published,
            'published_at' => Date::now()
        ]);
    }

    public function draft(): bool
    {
        return $this->update([
            'status' => NewsStatus::drafted,
        ]);
    }

    public function isArchived(): bool
    {
        return $this->status->is(NewsStatus::archived);
    }

    public function isPublished(): bool
    {
        return $this->status->is(NewsStatus::published);
    }

    public function isDrafted(): bool
    {
        return $this->status->is(NewsStatus::drafted);
    }
}
