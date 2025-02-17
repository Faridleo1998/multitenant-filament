<?php

namespace App\Domain\Classification\Models;

use App\Domain\Classification\Models\Attributes\CategoryAttributes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use CategoryAttributes;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
