<?php

namespace App\Domain\Admin\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait AdminAttributes
{
    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucfirst(strtolower($value))
        );
    }

    protected function email(): Attribute
    {
        return new Attribute(
            set: fn($value): string => strtolower($value)
        );
    }
}
