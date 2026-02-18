<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UslugaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'naziv' => $this->faker->word() . ' tretman',
            'opis' => $this->faker->sentence(),
            'kategorija' => $this->faker->randomElement(['sminkanje', 'manikir']),
            'trajanje_usluge' => $this->faker->randomElement([30, 45, 60, 90]),
            'cena' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}