<?php

namespace App\Domain\User\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CentralUserAttributes
{
    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value))
        );
    }

    protected function email(): Attribute
    {
        return new Attribute(
            set: fn($value): string => strtolower($value)
        );
    }
}
