<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\VolunteerResource\Pages\CreateVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\EditVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\ListVolunteers;
use App\Filament\Resources\VolunteerResource\Pages\ViewVolunteer;
use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Volunteer;
use Tests\Traits\ActingAsPlatformAdmin;

class AdminPlatformTest extends VolunteersBaseTest
{
    use ActingAsPlatformAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsPlatformAdmin();
    }

    public function testPlatformAdminCanViewVolunteers()
    {
        $volunteers = Volunteer::query()
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $organisation = Organisation::query()
            ->with('volunteers')
            ->inRandomOrder()
            ->first();

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
            ->assertCountTableRecords(10)
            ->assertCanSeeTableRecords($volunteers)
            ->assertCanRenderTableColumn('organisation.name')
            ->assertCanRenderTableColumn('full_name')
            ->assertCanRenderTableColumn('email')
            ->assertCanRenderTableColumn('phone')
            ->assertCanRenderTableColumn('specializations')
            ->assertCanRenderTableColumn('has_first_aid_accreditation')
            ->filterTable('organisation', $organisation->id)
            ->assertCanSeeTableRecords($organisation->volunteers)
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

    public function testPlatformAdminCanViewVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        \Livewire::test(ViewVolunteer::class, ['record' => $volunteer->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
            ->assertFormFieldIsDisabled('organisation_id')
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

        $volunteerTranslator = Volunteer::query()
            ->whereJsonContains('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        \Livewire::test(ViewVolunteer::class, ['record' => $volunteerTranslator->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
            ->assertFormFieldIsDisabled('organisation_id')
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

    public function testPlatformAdminCanEditVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        \Livewire::test(EditVolunteer::class, ['record' => $volunteer->id])
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
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
            ->fillForm(['specializations' => [$volunteer->specializations[] = VolunteerSpecialization::translator->value]])
            ->assertFormFieldIsVisible('language')
            ->assertFormFieldIsEnabled('language');
    }

    public function testPlatformAdminCanCreateVolunteer()
    {
        \Livewire::test(CreateVolunteer::class)
            ->assertSuccessful()
            ->assertFormFieldIsVisible('organisation_id')
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
                'organisation_id' => null,
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
                'organisation_id',
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
                'organisation_id' => Organisation::query()->max('id') + 1,
                'language' => fake()->word,
                'has_first_aid_accreditation' => rand(2, 99),
            ])
            ->call('create')
            ->assertHasNoFormErrors(['language'])
            ->assertHasFormErrors(['has_first_aid_accreditation', 'organisation_id'])
            ->fillForm([
                'specializations' => [VolunteerSpecialization::cook],
                'has_first_aid_accreditation' => rand(0, 1),
            ])
            ->assertFormFieldIsHidden('language')
            ->call('create')
            ->assertHasNoFormErrors(['has_first_aid_accreditation'])
            ->fillForm([
                'organisation_id' => Organisation::query()->inRandomOrder()->first()->id,
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
