<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\VolunteerResource\Pages\CreateVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\EditVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\ListVolunteers;
use App\Filament\Resources\VolunteerResource\Pages\ViewVolunteer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

class PlatformCoordinatorTest extends VolunteersBase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->platformCoordinator()
            ->create();

        $this->actingAs($this->user);
    }

    public function testPlatformCoordinatorCanViewVolunteers()
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

        Livewire::test(ListVolunteers::class)
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
            ->assertPageActionHidden('create')
            ->assertPageActionDisabled('create');
    }

    public function testPlatformCoordinatorCanViewVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        Livewire::test(ViewVolunteer::class, ['record' => $volunteer->id])
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
            ->assertPageActionDoesNotExist('edit')
            ->assertFormFieldIsHidden('language');

        $volunteerTranslator = Volunteer::query()
            ->whereJsonContains('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        Livewire::test(ViewVolunteer::class, ['record' => $volunteerTranslator->id])
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
            ->assertPageActionDoesNotExist('edit');
    }

    public function testPlatformCoordinatorCanNotEditVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        Livewire::test(EditVolunteer::class, ['record' => $volunteer->id])
            ->assertForbidden();
    }

    public function testPlatformCoordinatorCanNotCreateVolunteer()
    {
        Livewire::test(CreateVolunteer::class)
            ->assertForbidden();
    }
}
