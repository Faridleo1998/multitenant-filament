<?php

namespace Database\Factories;

use App\Domain\User\Models\CentralUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CentralUserFactory extends Factory
{
    protected static ?string $password;

    protected $model = CentralUser::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => preg_replace('/@example\..*/', '@' . config('system.domain'), fake()->unique()->safeEmail),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
