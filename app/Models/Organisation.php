<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasLocation;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    ];

    protected $casts = [
        'contact_person' => 'array',
        'other_information' => AsCollection::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function expertises()
    {
        return $this->belongsToMany(Expertise::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
