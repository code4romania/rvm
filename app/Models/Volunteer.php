<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasLocation;
use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;
    use HasLocation;

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'phone',
        'county_id',
        'city_id',
        'specializations',
        'cnp',
    ];

    protected $casts = [
        'role' => VolunteerRole::class,
        'specializations' => AsEnumCollection::class . ':' . VolunteerSpecialization::class,
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
