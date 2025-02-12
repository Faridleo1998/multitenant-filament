<?php

namespace App\Domain\Tenant\Models;

use App\Domain\Tenant\Models\Attributes\TenantAttributes;
use App\Domain\Tenant\Models\Relationships\TenantRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasFactory;
    use TenantAttributes, TenantRelationships;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'identification',
            'name',
            'phone',
            'email_contact',
            'address',
            'country_id',
            'state_id',
            'city_id',
        ];
    }
}
