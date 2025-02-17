<?php

namespace Database\Factories;

use App\Domain\Classification\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(2, true),
            'slug' => fake()->slug(),
            'is_active' => fake()->boolean(),
        ];
    }
}
