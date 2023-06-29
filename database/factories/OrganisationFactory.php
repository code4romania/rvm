<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganisationFactory extends Factory
{
    public function definition()
    {
        $contactPerson = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => fake()->jobTitle(),
        ];

        $otherInfo = [
            'facebook' => '#facebook',
            'website' => '#website',
        ];

        $city = City::query()->inRandomOrder()->first();
        $name = fake()->company();

        return [
            'name' => $name,
            'alias' => Str::slug($name),
            'type' => fake()->randomElement(OrganisationType::values()),
            'status' => fake()->randomElement(OrganisationStatus::values()),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'year' => fake()->year(),
            'cif' => fake()->numerify('#########'),
            'registration_number' => fake()->text(6),
            'description' => fake()->sentence('10'),
            'address' => fake()->address(),
            'contact_person' => $contactPerson,
            'other_information' => $otherInfo,
            'has_branches' => fake()->boolean(),
            'city_id' => $city->id,
            'county_id' => $city->county_id,
            'social_services_accreditation' => fake()->boolean(),
        ];
    }
}
