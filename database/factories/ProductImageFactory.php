<?php

namespace Database\Factories;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        return [
            'url' => $this->faker->imageUrl(),
            'product_id'=>rand(1,5),
        ];
    }
}
