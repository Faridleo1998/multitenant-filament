<?php

namespace App\Domain\User\Models\Relationships;

use App\Domain\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CentralUserRelationships
{
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users', 'global_user_id', 'tenant_id');
    }
}
