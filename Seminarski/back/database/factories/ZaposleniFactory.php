<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZaposleniFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['type' => 'sminkerka']), 
            'radni_staz' => $this->faker->numberBetween(1, 20),
        ];
    }
}