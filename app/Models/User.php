<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\BelongsToOrganisation;
use App\Concerns\HasRole;
use App\Concerns\LimitsVisibility;
use App\Concerns\MustSetInitialPassword;
use App\Enum\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName
{
    use BelongsToOrganisation;
    use HasApiTokens;
    use HasFactory;
    use HasRole;
    use Notifiable;
    use MustSetInitialPassword;
    use LimitsVisibility;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'password_set_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password_set_at' => 'datetime',
    ];

    public static function booted(): void
    {
        static::creating(function (self $user) {
            if ($user->role !== null) {
                return;
            }

            if ($user->belongsToOrganisation()) {
                $user->role = UserRole::ORG_ADMIN;
            }
        });
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
