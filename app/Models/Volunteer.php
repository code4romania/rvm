<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\BelongsToOrganisation;
use App\Concerns\HasLocation;
use App\Concerns\LimitsVisibility;
use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use BelongsToOrganisation;
    use HasFactory;
    use HasLocation;
    use LimitsVisibility;

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'phone',
        'county_id',
        'city_id',
        'specializations',
        'has_first_aid_accreditation',
        'cnp',
    ];

    protected $casts = [
        'role' => VolunteerRole::class,
        'specializations' => AsEnumCollection::class . ':' . VolunteerSpecialization::class,
        'has_first_aid_accreditation' => 'boolean',
    ];
}
