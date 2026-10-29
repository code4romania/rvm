<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\Volunteers\Pages\CreateVolunteer;
use App\Filament\Resources\Volunteers\Pages\EditVolunteer;
use App\Filament\Resources\Volunteers\Pages\ListVolunteers;
use App\Filament\Resources\Volunteers\Pages\ViewVolunteer;
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
            ->assertActionHidden('create')
            ->assertActionDisabled('create');
    }

    public function testPlatformCoordinatorCanViewVolunteer()
    {
        $volunteer = Volunteer::query()
            ->whereJsonDoesntContain('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        Livewire::test(ViewVolunteer::class, ['record' => $volunteer->id])
            ->assertSuccessful();

        $volunteerTranslator = Volunteer::query()
            ->whereJsonContains('specializations', VolunteerSpecialization::translator)
            ->inRandomOrder()
            ->first();

        Livewire::test(ViewVolunteer::class, ['record' => $volunteerTranslator->id])
            ->assertSuccessful();
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
