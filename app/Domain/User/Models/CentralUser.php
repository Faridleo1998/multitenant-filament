<?php

namespace App\Domain\User\Models;

use App\Domain\User\Models\Attributes\CentralUserAttributes;
use App\Domain\User\Models\Relationships\CentralUserRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class CentralUser extends Model implements SyncMaster
{
    use CentralConnection, ResourceSyncing, SoftDeletes;
    use CentralUserAttributes, CentralUserRelationships;

    public $incrementing = false;

    public $table = 'users';

    protected $keyType = 'string';

    protected $primaryKey = 'global_id';

    protected $guarded = [];

    public function getTenantModelName(): string
    {
        return User::class;
    }

    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'global_id';
    }

    public function getCentralModelName(): string
    {
        return static::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return [
            'name',
            'password',
            'email',
            'phone',
        ];
    }
}
