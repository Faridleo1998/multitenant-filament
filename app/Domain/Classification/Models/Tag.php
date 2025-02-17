<?php

namespace App\Domain\Classification\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
