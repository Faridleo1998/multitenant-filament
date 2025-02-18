<?php

namespace Database\Factories;

use App\Domain\Education\Models\Study;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyFactory extends Factory
{
    protected $model = Study::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
        ];
    }
}
