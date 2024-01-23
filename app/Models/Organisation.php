<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasLocation;
use App\Concerns\LimitsVisibility;
use App\Enum\DocumentType;
use App\Enum\NGOType;
use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use App\Events\OrganisationCreated;
use App\Models\Organisation\Branch;
use App\Models\Organisation\Expertise;
use App\Models\Organisation\ResourceType;
use App\Models\Organisation\RiskCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Organisation extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LimitsVisibility;
    use Notifiable;
    use HasLocation;

    protected $with = ['city', 'county'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, 80, 40)
            ->nonQueued();
    }

    protected $fillable = [
        'name',
        'alias',
        'type',
        'ngo_type',
        'status',
        'email',
        'phone',
        'year',
        'cif',
        'registration_number',
        'contact_person',
        'other_information',
        'description',
        'address',
        'type_of_area',
        'has_branches',
        'social_services_accreditation',
        'area',
    ];

    protected $casts = [
        'area' => OrganisationAreaType::class,
        'type' => OrganisationType::class,
        'ngo_type' => NGOType::class,
        'status' => OrganisationStatus::class,
        'contact_person' => 'array',
        'other_information' => AsCollection::class,
        'has_branches' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => OrganisationCreated::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function expertises(): BelongsToMany
    {
        return $this->belongsToMany(Expertise::class);
    }

    public function localities(): BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }

    public function riskCategories(): BelongsToMany
    {
        return $this->belongsToMany(RiskCategory::class);
    }

    public function resourceTypes(): BelongsToMany
    {
        return $this->belongsToMany(ResourceType::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function activityCounties(): MorphToMany
    {
        return $this->morphedByMany(County::class, 'model', 'model_has_organisations');
    }

    public function activityCities(): MorphToMany
    {
        return $this->morphedByMany(City::class, 'model', 'model_has_organisations');
    }

    public function scopeWithLastProtocolExpiresAt(Builder $query): Builder
    {
        return $query
            ->addSelect([
                'last_protocol_expires_at' => Document::select('expires_at')
                    ->whereColumn('organisation_id', 'organisations.id')
                    ->where('type', DocumentType::protocol)
                    ->orderByDesc('expires_at')
                    ->limit(1),
            ])
            ->withCasts([
                'last_protocol_expires_at' => 'date',
            ]);
    }

    public function scopeWhereActive(Builder $query): Builder
    {
        return $query->where('status', OrganisationStatus::active);
    }

    public function setActive(): bool
    {
        return $this->update([
            'status' => OrganisationStatus::active,
        ]);
    }

    public function setInactive(): bool
    {
        return $this->update([
            'status' => OrganisationStatus::inactive,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status->is(OrganisationStatus::active);
    }

    public function isInactive(): bool
    {
        return $this->status->is(OrganisationStatus::inactive);
    }

    public function routeNotificationForMail(?Notification $notification = null): string
    {
        return data_get($this->contact_person, ['email'], $this->email);
    }
}
