<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\VolunteerRole;
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
        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'role' => fake()->randomElement(VolunteerRole::values()),
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
            'accreditation' => fake()->boolean
        ];
    }
}
