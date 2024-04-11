<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Enum\DocumentType;
use App\Enum\OrganisationStatus;
use App\Enum\UserRole;
use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\User;
use Database\Seeders\ResourceCategorySeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Livewire;
use Tests\TestCase;

abstract class VolunteersBaseTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = ResourceCategorySeed::class;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        $this->createOrganisationsWithVolunteers();
    }

    protected function createOrganisationsWithVolunteers()
    {
        Organisation::factory()
            ->count(5)
            ->withUserAndVolunteers()
            ->create();
    }
}
