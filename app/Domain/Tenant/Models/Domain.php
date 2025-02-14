<?php

namespace App\Domain\Tenant\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'domain',
        'tenant_id',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected function domain(): Attribute
    {
        return new Attribute(
            set: fn($value): string => $value . '.' . config('system.domain'),
        );
    }
}
