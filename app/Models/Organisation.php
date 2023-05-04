<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasLocation;
use App\Models\Organisation\Expertise;
use App\Models\Organisation\ResourceType;
use App\Models\Organisation\RiskCategory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasFactory;
    use HasLocation;

    protected $fillable = [
        'name',
        'alias',
        'type',
        'status',
        'email',
        'phone',
        'year',
        'vat',
        'no_registration',
        'contact_person',
        'other_information',
        'description',
        'short_description',
        'type_of_area',

    ];

    protected $casts = [
        'contact_person' => 'array',
        'other_information' => AsCollection::class,
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

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
