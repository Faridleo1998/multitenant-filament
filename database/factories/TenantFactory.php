<?php

namespace Database\Factories;

use App\Domain\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'identification' => fake()->unique()->numerify('##########'),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
