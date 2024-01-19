<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\UserRole;
use App\Models\Organisation;
use App\Models\User;
use App\Notifications\ExpiredProtocol;
use App\Notifications\ExpiringProtocol;
use App\Notifications\SummaryExpiredProtocols;
use App\Notifications\SummaryExpiringProtocols;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Collection;

class ProcessProtocolsCommand extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:protocols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process protocols that are about to expire';

    public Collection $admins;

    public Collection $organisations;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->loadPlatformAdmins();
        $this->loadActiveOrganisations();

        logger()->info(sprintf(
            'ProcessProtocols: starting check on %d active organisations...',
            $this->organisations->count()
        ));

        $this->handleExpiringProtocols();
        $this->handleExpiredProtocols();

        logger()->info('ProcessProtocols: done!');

        return static::SUCCESS;
    }

    private function loadPlatformAdmins(): void
    {
        $this->admins = User::query()
            ->withoutGlobalScopes()
            ->role(UserRole::PLATFORM_ADMIN)
            ->get();
    }

    private function loadActiveOrganisations(): void
    {
        $this->organisations = Organisation::query()
            ->whereActive()
            ->select(['id', 'name', 'email', 'contact_person'])
            ->withOnly([
                'users' => function ($query) {
                    $query->select(['id', 'email', 'organisation_id'])
                        ->role(UserRole::ORG_ADMIN);
                },
            ])
            ->withLastProtocolExpiresAt()
            ->get();
    }

    private function handleExpiringProtocols(): void
    {
        $checkDate = today()->addDays(30);

        logger()->info(sprintf(
            'ProcessProtocols: checking for protocols expiring in 30 days (%s)...',
            $checkDate->format('Y-m-d')
        ));

        $this->organisations
            ->filter(
                fn (Organisation $organisation) => $organisation
                    ->last_protocol_expires_at
                    ?->isSameDay($checkDate) ?? true
            )
            ->each(function (Organisation $organisation) {
                $this->sendNotification(ExpiringProtocol::class, $organisation);
            })
            ->tap(function (Collection $organisations) {
                logger()->info(sprintf(
                    'ProcessProtocols: found %d organisations with expiring protocols: %s',
                    $organisations->count(),
                    $organisations->pluck('id')->join(', ')
                ));

                $this->sendSummaryNotification(SummaryExpiringProtocols::class, $organisations);
            });
    }

    private function handleExpiredProtocols(): void
    {
        $checkDate = today();

        logger()->info(sprintf(
            'ProcessProtocols: checking for protocols expiring today (%s)...',
            $checkDate->format('Y-m-d')
        ));

        $this->organisations
            ->filter(
                fn (Organisation $organisation) => $organisation
                    ->last_protocol_expires_at
                    ?->lte($checkDate) ?? true
            )
            ->each(function (Organisation $organisation) {
                $organisation->setInactive();

                $this->sendNotification(ExpiredProtocol::class, $organisation);
            })
            ->tap(function (Collection $organisations) {
                logger()->info(sprintf(
                    'ProcessProtocols: found %d organisations with expired protocols: %s',
                    $organisations->count(),
                    $organisations->pluck('id')->join(', ')
                ));

                $this->sendSummaryNotification(SummaryExpiredProtocols::class, $organisations);
            });
    }

    private function sendNotification(string $notification, Organisation $organisation): void
    {
        $organisation->notify(new $notification($organisation));

        $organisation->users
            ->each(
                fn (User $user) => $user->notify(new $notification($organisation))
            );
    }

    private function sendSummaryNotification(string $notification, Collection $organisations): void
    {
        if ($organisations->isEmpty()) {
            return;
        }

        $this->admins->each(
            fn (User $user) => $user->notify(new $notification($organisations))
        );
    }
}
