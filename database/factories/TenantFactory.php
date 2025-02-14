<?php

namespace Database\Factories;

use App\Domain\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'identification' => fake()->unique()->numerify('##########'),
            'name' => $name = fake()->name(),
            'domain' => Str::slug($name),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
