<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteer>
 */
class VolunteerFactory extends Factory
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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'cnp' => fake()->cnp(),
            'role' => fake()->randomElement(VolunteerRole::values()),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'city_id' => $city->id,
            'county_id' => $city->county_id,
            'specializations' => fake()->randomElements(VolunteerSpecialization::values(), fake()->numberBetween(1, 3)),
            'has_first_aid_accreditation' => fake()->boolean(),
        ];
    }

    public function translator(): static
    {
        return $this->state(fn ($attributes) => [
            'specializations' => array_merge($attributes['specializations'], [VolunteerSpecialization::translator]),
        ]);
    }
}
