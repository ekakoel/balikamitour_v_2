<?php

namespace Database\Factories;


use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween($min = 100000, $max = 900000),
            'category_id' => 5,
            'photo' => 'images/cars/cima_1912_top_01.jpg.ximg.l_full_m.smart.jpg',
        ];
    }
}
