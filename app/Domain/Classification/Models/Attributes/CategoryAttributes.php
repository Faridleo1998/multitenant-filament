<?php

namespace App\Domain\Classification\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CategoryAttributes
{
    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucfirst(strtolower($value))
        );
    }

    protected function slug(): Attribute
    {
        return new Attribute(
            set: fn($value): string => strtolower($value)
        );
    }
}
