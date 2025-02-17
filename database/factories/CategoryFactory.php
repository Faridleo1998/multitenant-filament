<?php

namespace Database\Factories;

use App\Domain\Classification\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->word(2, true),
            'slug' => Str::slug($name),
            'is_active' => fake()->boolean(),
        ];
    }
}
