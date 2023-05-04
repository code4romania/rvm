<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable=[
        'contact_person_name',
        'email',
        'phone',
        'address',
    ];
}
