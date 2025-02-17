<?php

namespace App\Domain\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domain\User\Models\Attributes\UserAttributes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Contracts\Syncable;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class User extends Authenticatable implements FilamentUser, Syncable
{
    use HasRoles, Notifiable, ResourceSyncing, SoftDeletes;
    use UserAttributes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'global_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getSyncedAttributeNames(): array
    {
        return [
            'name',
            'password',
            'email',
            'phone',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canBeImpersonated(): bool
    {
        return true;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
