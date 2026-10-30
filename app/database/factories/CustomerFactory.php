<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'email'       => $this->faker->unique()->safeEmail(),
            'phone'       => $this->faker->phoneNumber(),
            'street'      => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'city'        => $this->faker->city(),
            'country'     => 'NL',
        ];
    }
}
