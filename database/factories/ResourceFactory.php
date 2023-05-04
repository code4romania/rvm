<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $city = City::query()->inRandomOrder()->first();
        return [
            'name' => fake()->name,
            'quantity' => fake()->numberBetween(),
            'type' => fake()->randomElement(['tip1', 'tip2']),
            'has_transport' => fake()->boolean,
            'contact_name' => fake()->name,
            'contact_phone' => fake()->phoneNumber,
            'contact_email' => fake()->email,
            'city_id' => $city->id,
            'county_id' => $city->county_id,
            'observation' => fake()->sentence(10),
        ];
    }
}
