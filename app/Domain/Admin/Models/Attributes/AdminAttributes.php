<?php

namespace App\Domain\Admin\Models\Attributes;

use App\Domain\Admin\Models\Admin;
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

    protected function isSuperAdmin(): Attribute
    {
        return new Attribute(
            get: fn(): bool => $this->id === Admin::SUPER_ADMIN_ID
        );
    }
}
