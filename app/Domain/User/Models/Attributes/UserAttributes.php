<?php

namespace App\Domain\User\Models\Attributes;

use App\Domain\User\Models\CentralUser;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value))
        );
    }
}
