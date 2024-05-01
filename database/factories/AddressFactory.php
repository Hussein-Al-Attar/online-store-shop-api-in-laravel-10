<?php

namespace Database\Factories;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'user_id' => rand(1,10),
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'address_line_1'=>$this->faker->word,
            'address_line_2'=>$this->faker->word,
        ];
    }
}

