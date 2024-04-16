<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Enum\UserRole;
use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\VolunteerResource\Pages\CreateVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\EditVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\ListVolunteers;
use App\Filament\Resources\VolunteerResource\Pages\ViewVolunteer;
use App\Models\City;
use App\Models\County;
use App\Models\User;
use App\Models\Volunteer;

class AdminOrgTest extends VolunteersBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::query()
            ->where('role', UserRole::ORG_ADMIN)
            ->inRandomOrder()
            ->first();
        $this->actingAs($this->user);
    }

    public function testAdminOngCanViewVolunteers()
    {
        $volunteers = Volunteer::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $county = $volunteers->first()->county;
        $volunteersFromCounty = Volunteer::query()
            ->where('county_id', $county->id)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $specialization = fake()->randomElements(VolunteerSpecialization::values());
        $specializationVolunteers = Volunteer::query()
            ->whereJsonContains('specializations', $specialization)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $hasFirstAid = fake()->boolean;
        $firstAidVolunteers = Volunteer::query()
            ->where('has_first_aid_accreditation', $hasFirstAid)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        \Livewire::test(ListVolunteers::class)
            ->assertSuccessful()
            ->assertCountTableRecords(5)
            ->assertCanSeeTableRecords($volunteers)
            ->assertCanNotRenderTableColumn('organisation.name')
            ->assertCanRenderTableColumn('full_name')
            ->assertCanRenderTableColumn('email')
            ->assertCanRenderTableColumn('phone')
            ->assertCanRenderTableColumn('specializations')
            ->assertCanRenderTableColumn('has_first_aid_accreditation')
            ->resetTableFilters()
            ->filterTable('county', $county->id)
            ->assertCanSeeTableRecords($volunteersFromCounty)
            ->resetTableFilters()
            ->filterTable('specializations', $specialization)
            ->assertCanSeeTableRecords($specializationVolunteers)
            ->resetTableFilters()
            ->filterTable('has_first_aid_accreditation', $hasFirstAid)
            ->assertCanSeeTableRecords($firstAidVolunteers)
            ->assertPageActionVisible('create')
            ->assertPageActionEnabled('create');
    }

    public function testAdminOngCanViewVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        \Livewire::test(ViewVolunteer::class, ['record' => $volunteer->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('first_name')
            ->assertFormFieldIsDisabled('first_name')
            ->assertFormFieldIsVisible('last_name')
            ->assertFormFieldIsDisabled('last_name')
            ->assertFormFieldIsVisible('email')
            ->assertFormFieldIsDisabled('email')
            ->assertFormFieldIsVisible('phone')
            ->assertFormFieldIsDisabled('phone')
            ->assertFormFieldIsVisible('cnp')
            ->assertFormFieldIsDisabled('cnp')
            ->assertFormFieldIsVisible('role')
            ->assertFormFieldIsDisabled('role')
            ->assertFormFieldIsVisible('specializations')
            ->assertFormFieldIsDisabled('specializations')
            ->assertFormFieldIsVisible('has_first_aid_accreditation')
            ->assertFormFieldIsDisabled('has_first_aid_accreditation')
            ->assertFormFieldIsVisible('county_id')
            ->assertFormFieldIsDisabled('county_id')
            ->assertFormFieldIsVisible('city_id')
            ->assertFormFieldIsDisabled('city_id')
            ->assertPageActionVisible('edit')
            ->assertPageActionEnabled('edit')
            ->assertFormFieldIsHidden('language');

        $volunteerTranslator = Volunteer::factory()
            ->for($this->user->organisation)
            ->state(['specializations' => VolunteerSpecialization::translator])
            ->create();

        \Livewire::test(ViewVolunteer::class, ['record' => $volunteerTranslator->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('first_name')
            ->assertFormFieldIsDisabled('first_name')
            ->assertFormFieldIsVisible('last_name')
            ->assertFormFieldIsDisabled('last_name')
            ->assertFormFieldIsVisible('email')
            ->assertFormFieldIsDisabled('email')
            ->assertFormFieldIsVisible('phone')
            ->assertFormFieldIsDisabled('phone')
            ->assertFormFieldIsVisible('cnp')
            ->assertFormFieldIsDisabled('cnp')
            ->assertFormFieldIsVisible('role')
            ->assertFormFieldIsDisabled('role')
            ->assertFormFieldIsVisible('specializations')
            ->assertFormFieldIsDisabled('specializations')
            ->assertFormFieldIsVisible('language')
            ->assertFormFieldIsDisabled('language')
            ->assertFormFieldIsVisible('has_first_aid_accreditation')
            ->assertFormFieldIsDisabled('has_first_aid_accreditation')
            ->assertFormFieldIsVisible('county_id')
            ->assertFormFieldIsDisabled('county_id')
            ->assertFormFieldIsVisible('city_id')
            ->assertFormFieldIsDisabled('city_id')
            ->assertPageActionVisible('edit')
            ->assertPageActionEnabled('edit');
    }

    public function testAdminOngCanEditVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        \Livewire::test(EditVolunteer::class, ['record' => $volunteer->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('first_name')
            ->assertFormFieldIsEnabled('first_name')
            ->assertFormFieldIsVisible('last_name')
            ->assertFormFieldIsEnabled('last_name')
            ->assertFormFieldIsVisible('email')
            ->assertFormFieldIsEnabled('email')
            ->assertFormFieldIsVisible('phone')
            ->assertFormFieldIsEnabled('phone')
            ->assertFormFieldIsVisible('cnp')
            ->assertFormFieldIsEnabled('cnp')
            ->assertFormFieldIsVisible('role')
            ->assertFormFieldIsEnabled('role')
            ->assertFormFieldIsVisible('specializations')
            ->assertFormFieldIsEnabled('specializations')
            ->assertFormFieldIsHidden('language')
            ->assertFormFieldIsVisible('has_first_aid_accreditation')
            ->assertFormFieldIsEnabled('has_first_aid_accreditation')
            ->assertFormFieldIsVisible('county_id')
            ->assertFormFieldIsEnabled('county_id')
            ->assertFormFieldIsVisible('city_id')
            ->assertFormFieldIsEnabled('city_id')
            ->fillForm(['specializations' => [$volunteer->specializations[] = VolunteerSpecialization::translator->value]])
            ->assertFormFieldIsVisible('language')
            ->assertFormFieldIsEnabled('language');
    }

    public function testAdminOngCanCreateVolunteer()
    {
        \Livewire::test(CreateVolunteer::class)
            ->assertSuccessful()
            ->assertFormFieldIsHidden('organisation_id')
            ->assertFormFieldIsEnabled('organisation_id')
            ->assertFormFieldIsVisible('first_name')
            ->assertFormFieldIsEnabled('first_name')
            ->assertFormFieldIsVisible('last_name')
            ->assertFormFieldIsEnabled('last_name')
            ->assertFormFieldIsVisible('email')
            ->assertFormFieldIsEnabled('email')
            ->assertFormFieldIsVisible('phone')
            ->assertFormFieldIsEnabled('phone')
            ->assertFormFieldIsVisible('cnp')
            ->assertFormFieldIsEnabled('cnp')
            ->assertFormFieldIsVisible('role')
            ->assertFormFieldIsEnabled('role')
            ->assertFormFieldIsVisible('specializations')
            ->assertFormFieldIsEnabled('specializations')
            ->assertFormFieldIsHidden('language')
            ->assertFormFieldIsVisible('has_first_aid_accreditation')
            ->assertFormFieldIsEnabled('has_first_aid_accreditation')
            ->assertFormFieldIsVisible('county_id')
            ->assertFormFieldIsEnabled('county_id')
            ->assertFormFieldIsVisible('city_id')
            ->assertFormFieldIsEnabled('city_id')
            ->fillForm([
                'first_name' => fake()->realTextBetween(256, 300),
                'last_name' => fake()->realTextBetween(256, 300),
                'email' => fake()->phoneNumber,
                'phone' => fake()->word,
                'cnp' => fake()->word,
                'role' => fake()->randomElement(VolunteerRole::values()),
                'specializations' => [VolunteerSpecialization::translator->value],
                'has_first_aid_accreditation' => fake()->word,
                'county_id' => null,
                'city_id' => null,
            ])
            ->assertFormFieldIsVisible('language')
            ->call('create')
            ->assertHasFormErrors([
                'first_name',
                'last_name',
                'email',
                'phone',
                'cnp',
                'language',
                'has_first_aid_accreditation',
                'county_id',
                'city_id',
            ])
            ->fillForm([
                'language' => fake()->word,
                'has_first_aid_accreditation' => rand(2, 99),
            ])
            ->call('create')
            ->assertHasNoFormErrors(['language'])
            ->assertHasFormErrors(['has_first_aid_accreditation'])
            ->fillForm([
                'specializations' => [VolunteerSpecialization::cook],
                'has_first_aid_accreditation' => rand(0, 1),
            ])
            ->assertFormFieldIsHidden('language')
            ->call('create')
            ->assertHasNoFormErrors(['has_first_aid_accreditation'])
            ->fillForm([
                'first_name' => fake()->firstName,
                'last_name' => fake()->lastName,
                'email' => fake()->email,
                'phone' => fake()->phoneNumber,
                'cnp' => fake()->cnp(),
                'role' => fake()->randomElement(VolunteerRole::values()),
                'specializations' => [VolunteerSpecialization::cook->value, VolunteerSpecialization::search_rescue->value],
                'has_first_aid_accreditation' => fake()->boolean,
                'county_id' => County::query()->inRandomOrder()->first()->id,
                'city_id' => City::query()->inRandomOrder()->first()->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    }
}
