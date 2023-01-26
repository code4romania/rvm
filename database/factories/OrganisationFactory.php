<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class OrganisationFactory extends Factory
{
    public function definition()
    {
        $contactPerson = [
            'contact_name' => fake()->name,
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
        ];
        $otherInfo = [
            'facebook' => '#facebook',
            'website' => '#website',
        ];
        $name = fake()->company;

        return [
            'name' => $name,
            'alias' => Str::slug($name),
            'type' => fake()->randomElement(OrganisationType::values()),
            'status' => fake()->randomElement(OrganisationStatus::values()),
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
            'year' => fake()->year,
            'vat' => fake()->text(6),
            'no_registration' => fake()->text(6),
            'description' => fake()->sentence('10'),
            'short_description' => fake()->sentence('10'),
            'contact_person' => $contactPerson,
            'other_information' => $otherInfo,
        ];
    }
}
