<?php

declare(strict_types=1);

namespace Tests\Feature\Volunteers;

use App\Models\Organisation;
use App\Models\User;
use Database\Seeders\ResourceCategorySeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
            ->count(2)
            ->withUserAndVolunteers()
            ->create();
    }
}
