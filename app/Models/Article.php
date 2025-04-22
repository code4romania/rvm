<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Enum\ArticleStatus;
use App\Concerns\LimitsVisibility;
use App\Concerns\BelongsToOrganisation;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model implements HasMedia
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
        'status'
    ];

    protected $casts = [
        'status' => ArticleStatus::class,
    ];

    public function scopeWherePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::published);
    }

    public function archive(): bool
    {
        return $this->update([
            'status' => ArticleStatus::archived,
        ]);
    }

    public function publish(): bool
    {
        return $this->update([
            'status' => ArticleStatus::published,
        ]);
    }


    public function draft(): bool
    {
        return $this->update([
            'status' => ArticleStatus::drafted,
        ]);
    }

    public function isArchived(): bool
    {
        return $this->status->is(ArticleStatus::archived);
    }

    public function isPublished(): bool
    {
        return $this->status->is(ArticleStatus::published);
    }


    public function isDrafted(): bool
    {
        return $this->status->is(ArticleStatus::drafted);
    }
}
