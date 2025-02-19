<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Currency;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price_amount' => fake()->randomFloat(2, 100, 100000),
            'price_currency' => fake()->randomElement(Currency::cases()),
            'type' => fake()->randomElement(ProductType::cases()),
            'sold_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
