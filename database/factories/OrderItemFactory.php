<?php

namespace Database\Factories;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => function () {
                return \App\Models\Order::factory()->create()->id;
            },
            'product_id' => function () {
                return \App\Models\Product::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 10, 100),
            // Add more attributes as needed
        ];
    }
}
