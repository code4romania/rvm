<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use App\Models\City;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\Organisation\Branch;
use App\Models\Organisation\Expertise;
use App\Models\Organisation\RiskCategory;
use App\Models\Resource;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
            'areas' => fake()->randomElements(
                OrganisationAreaType::values(),
                fake()->numberBetween(0, 4)
            ),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Organisation $organisation) {
            Volunteer::factory()
                ->for($organisation)
                ->count(fake()->numberBetween(0, 100))
                ->create();

            Resource::factory()
                ->for($organisation)
                ->count(fake()->randomDigitNotZero())
                ->create();

            Document::factory()
                ->for($organisation)
                ->count(fake()->randomDigitNotZero())
                ->create();

            $organisation->expertises()
                ->attach($this->randomExpertises(3));

            $organisation->riskCategories()
                ->attach($this->randomRiskCategories(3));

            if ($organisation->has_branches) {
                Branch::factory()
                    ->for($organisation)
                    ->count(fake()->numberBetween(1, 3))
                    ->create();
            }
        });
    }

    private function randomExpertises(int $count = 1): Collection
    {
        return Cache::driver('array')
            ->rememberForever('expertises', fn () => Expertise::pluck('id'))
            ->random($count);
    }

    protected function randomRiskCategories(int $count = 1): Collection
    {
        return Cache::driver('array')
            ->rememberForever('risk_categories', fn () => RiskCategory::pluck('id'))
            ->random($count);
    }
}
