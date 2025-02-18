<?php

namespace App\Domain\Education\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    protected $fillable = [
        'name',
    ];

    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucfirst(strtolower($value))
        );
    }
}
