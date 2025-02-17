<?php

namespace Database\Factories;

use App\Domain\Classification\Models\Keyword;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeywordFactory extends Factory
{
    protected $model = Keyword::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
