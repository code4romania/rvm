<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use App\Models\Resource\Subcategory;
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
        $subcategory = Subcategory::query()->inRandomOrder()->first();
        $contactPerson = [
            'person' => fake()->name,
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
        ];

        return [
            'name' => fake()->name,
            'contact' => $contactPerson,
            'city_id' => $city->id,
            'county_id' => $city->county_id,
            'subcategory_id' => $subcategory?->id,
            'category_id' => $subcategory?->category_id,
            'type_id' => fake()->randomElement($subcategory?->types?->pluck('id')->toArray() ?? []),
            'observation' => fake()->sentence(10),
        ];
    }
}
