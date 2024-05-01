<?php

namespace Database\Factories;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
            // Add more attributes as needed
        ];
    }
}
