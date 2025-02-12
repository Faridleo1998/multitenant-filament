<?php

namespace App\Domain\User\Models\Attributes;

use App\Domain\User\Models\CentralUser;

trait UserAttributes
{
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
        return CentralUser::class;
    }
}
