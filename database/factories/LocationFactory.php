<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
  public function definition(): array
  {
    return [
      'city' => fake()->city(),
      'country' => 'Polska',
      'region' => fake()->state(),
    ];
  }
}
