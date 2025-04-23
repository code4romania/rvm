<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\NGOType;
use App\Enum\OrganisationAreaType;
use App\Enum\OrganisationStatus;
use App\Enum\OrganisationType;
use App\Models\City;
use App\Models\County;
use App\Models\Document;
use App\Models\News;
use App\Models\Organisation;
use App\Models\Organisation\Branch;
use App\Models\Organisation\Expertise;
use App\Models\Organisation\RiskCategory;
use App\Models\Resource;
use App\Models\User;
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
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => fake()->jobTitle(),
        ];

        $contactPersonInTeams = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'role' => fake()->jobTitle(),
        ];

        $otherInfo = [
            'facebook' => fake()->boolean() ? 'https://www.facebook.com/#link' : null,
            'website' => fake()->boolean() ? fake()->url() : null,
        ];

        $city = City::query()->inRandomOrder()->first();
        $name = fake()->company();
        $type = fake()->randomElement(OrganisationType::values());

        return [
            'name' => $name,
            'alias' => Str::slug($name),
            'type' => $type,
            'ngo_type' => OrganisationType::ngo->is($type)
                ? fake()->randomElement(NGOType::values())
                : null,
            'status' => OrganisationStatus::active,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'year' => fake()->year(),
            'cif' => null,
            'registration_number' => fake()->text(6),
            'description' => fake()->sentence('10'),
            'address' => fake()->address(),
            'contact_person' => $contactPerson,
            'contact_person_in_teams' => $contactPersonInTeams,
            'other_information' => $otherInfo,
            'has_branches' => fake()->boolean(),
            'city_id' => $city->id,
            'county_id' => $city->county_id,
            'social_services_accreditation' => fake()->boolean(),
            'area' => fake()->randomElement(OrganisationAreaType::values()),
        ];
    }

    public function inactive(): static
    {
        return $this->state([
            'status' => OrganisationStatus::inactive,
        ]);
    }

    public function randomStatus(): static
    {
        return $this->state(fn () => [
            'status' => fake()->randomElement(OrganisationStatus::values()),
        ]);
    }

    public function withRelated(): static
    {
        return $this->afterCreating(function (Organisation $organisation) {
            User::factory(['email' => $organisation->email])
                ->orgAdmin()
                ->for($organisation)
                ->create();

            Volunteer::factory()
                ->for($organisation)
                ->count(20)
                ->create();

            Resource::factory()
                ->for($organisation)
                ->count(fake()->randomDigitNotZero())
                ->create();

            Document::factory()
                ->for($organisation)
                ->create();

            Document::factory()
                ->contract()
                ->for($organisation)
                ->create();

            Document::factory()
                ->protocol()
                ->for($organisation)
                ->create();

            $organisation->expertises()
                ->attach($this->randomExpertises(3));

            $organisation->riskCategories()
                ->attach($this->randomRiskCategories(3));

            $organisation->resourceTypes()
                ->attach($this->randomResourceTypes(3));

            if ($organisation->has_branches) {
                Branch::factory()
                    ->for($organisation)
                    ->count(fake()->numberBetween(1, 3))
                    ->create();
            }

            News::factory()
                ->for($organisation)
                ->count(fake()
                    ->randomDigitNotZero())
                ->create();

            $this->attachLocationByActivityArea($organisation);
        });
    }

    public function withUserAndDocuments()
    {
        return $this->afterCreating(function (Organisation $organisation) {
            User::factory(['email' => $organisation->email])
                ->orgAdmin()
                ->for($organisation)
                ->create();

            Document::factory()
                ->for($organisation)
                ->create();

            Document::factory()
                ->contract()
                ->for($organisation)
                ->create();

            Document::factory()
                ->protocol()
                ->for($organisation)
                ->create();
        });
    }

    public function withUserAndVolunteers()
    {
        return $this->afterCreating(function (Organisation $organisation) {
            // User::factory(['email' => $organisation->email])
            //     ->orgAdmin()
            //     ->for($organisation)
            //     ->create();

            Volunteer::factory()
                ->for($organisation)
                ->translator()
                ->create();

            Volunteer::factory()
                ->for($organisation)
                ->count(4)
                ->create();
        });
    }

    protected function attachLocationByActivityArea(Organisation $organisation): void
    {
        $counties = null;
        $cities = null;

        if ($organisation->area->is(OrganisationAreaType::local)) {
            $cities = City::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 4))
                ->get();

            $counties = $cities->pluck('county_id')
                ->unique()
                ->values();
        }

        if ($organisation->area->is(OrganisationAreaType::regional)) {
            $counties = County::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 4))
                ->get();
        }

        if ($cities !== null) {
            $organisation->activityCities()->attach($cities);
        }

        if ($counties !== null) {
            $organisation->activityCounties()->attach($counties);
        }
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

    protected function randomResourceTypes(int $count = 1): Collection
    {
        return Cache::driver('array')
            ->rememberForever('resource_types', fn () => Organisation\ResourceType::pluck('id'))
            ->random($count);
    }
}
