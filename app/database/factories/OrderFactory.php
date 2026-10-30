<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number'      => 'ORD-'.$this->faker->unique()->numberBetween(10000, 99999),
            'customer_id' => Customer::factory(),
            'status'      => $this->faker->randomElement(array_keys(Order::STATUSES)),
            'total'       => 0,
            'notes'       => $this->faker->optional(0.3)->sentence(),
            'ordered_at'  => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
