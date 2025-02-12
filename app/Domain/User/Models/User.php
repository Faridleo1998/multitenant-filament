<?php

namespace App\Domain\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domain\User\Models\Attributes\UserAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Contracts\Syncable;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class User extends Authenticatable implements Syncable
{
    use Notifiable, ResourceSyncing, SoftDeletes;
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
