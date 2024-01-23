<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Console\Commands\ProcessProtocolsCommand;
use App\Enum\UserRole;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\User;
use App\Notifications\ExpiredProtocol;
use App\Notifications\ExpiringProtocol;
use App\Notifications\SummaryExpiredProtocols;
use App\Notifications\SummaryExpiringProtocols;
use Database\Seeders\ResourceCategorySeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProtocolsTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = ResourceCategorySeed::class;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        User::factory()
            ->platformAdmin()
            ->create();
    }

    protected function getPlatformAdmins(): Collection
    {
        return User::query()
            ->withoutGlobalScopes()
            ->role(UserRole::PLATFORM_ADMIN)
            ->get();
    }

    /** @test */
    public function it_does_not_send_notifications_for_protocols_expiring_in_less_than_30_days(): void
    {
        $document = Document::factory()
            ->protocol()
            ->state([
                'expires_at' => today()->addDays(29),
            ])
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertNotSentTo(
            $document->organisation,
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $document->organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiringProtocols::class
        );
    }

    /** @test */
    public function it_sends_notifications_for_protocols_expiring_in_exactly_30_days(): void
    {
        $document = Document::factory()
            ->protocol()
            ->state([
                'expires_at' => today()->addDays(30),
            ])
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertSentTo(
            $document->organisation,
            ExpiringProtocol::class
        );

        Notification::assertSentTo(
            $document->organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiringProtocol::class
        );

        Notification::assertSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiringProtocols::class
        );
    }

    /** @test */
    public function it_does_not_send_notifications_for_protocols_expiring_in_more_than_30_days(): void
    {
        $document = Document::factory()
            ->protocol()
            ->state([
                'expires_at' => today()->addDays(31),
            ])
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertNotSentTo(
            $document->organisation,
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $document->organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiringProtocols::class
        );
    }

    /** @test */
    public function it_sends_notifications_for_protocols_that_expire_today(): void
    {
        $document = Document::factory()
            ->protocol()
            ->state([
                'expires_at' => today(),
            ])
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertSentTo(
            $document->organisation,
            ExpiredProtocol::class
        );

        Notification::assertSentTo(
            $document->organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiredProtocol::class
        );

        Notification::assertSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiredProtocols::class
        );
    }

    /** @test */
    public function it_sends_notifications_for_protocols_that_have_expired_in_the_past(): void
    {
        $document = Document::factory()
            ->protocol()
            ->state([
                'expires_at' => today()->subDays(3),
            ])
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertSentTo(
            $document->organisation,
            ExpiredProtocol::class
        );

        Notification::assertSentTo(
            $document->organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiredProtocol::class
        );

        Notification::assertSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiredProtocols::class
        );
    }

    /** @test */
    public function it_does_not_send_notifications_for_organisations_without_protocols(): void
    {
        $organisation = Organisation::factory()
            ->create();

        $this->artisan(ProcessProtocolsCommand::class)
            ->assertSuccessful();

        Notification::assertNotSentTo(
            $organisation,
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $organisation
                ->users
                ->where('role', UserRole::ORG_ADMIN),
            ExpiringProtocol::class
        );

        Notification::assertNotSentTo(
            $this->getPlatformAdmins(),
            SummaryExpiringProtocols::class
        );
    }
}
