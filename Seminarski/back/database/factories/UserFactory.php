<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ime' => $this->faker->word(),
            'prezime' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'type' => $this->faker->randomElement(['klijent', 'sminkerka', 'manikirka']),
        ];
    }

    // Stanje za vlasnicu
    public function vlasnica(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'vlasnica',
        ]);
    }
}