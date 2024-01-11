<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\UserRole;
use App\Models\Organisation;
use App\Models\User;
use App\Notifications\ExpiredProtocol;
use App\Notifications\ExpiringProtocol;
use App\Notifications\SummaryExpiredProtocols;
use App\Notifications\SummaryExpiringProtocols;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProcessProtocolsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Collection $admins;

    public function __construct()
    {
        $this->admins = User::query()
            ->withoutGlobalScopes()
            ->role(UserRole::PLATFORM_ADMIN)
            ->get();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $organisations = Organisation::query()
            ->select(['id', 'name', 'email', 'contact_person'])
            ->withOnly([
                'users' => function ($query) {
                    $query->select(['id', 'email', 'organisation_id'])
                        ->role(UserRole::ORG_ADMIN);
                },
            ])
            ->withLastProtocolExpiresAt()
            ->get();

        logger()->info(sprintf('ProcessProtocolsJob: starting check on %d organisations...', $organisations->count()));

        $this->handleExpiringProtocols($organisations);
        $this->handleExpiredProtocols($organisations);

        logger()->info('ProcessProtocolsJob: done!');
    }

    private function handleExpiringProtocols(Collection $organisations): void
    {
        $checkDate = today()->addDays(30);

        logger()->info(sprintf(
            'ProcessProtocolsJob: checking for protocols expiring in 30 days (%s)...',
            $checkDate->format('Y-m-d')
        ));

        $organisations
            ->filter(fn (Organisation $organisation) => $checkDate->isSameDay($organisation->last_protocol_expires_at))
            ->each(function (Organisation $organisation) {
                $this->sendNotification(ExpiringProtocol::class, $organisation);
            })
            ->tap(function (Collection $organisations) {
                logger()->info(sprintf(
                    'ProcessProtocolsJob: found %d organisations with expiring protocols: %s',
                    $organisations->count(),
                    $organisations->pluck('id')->join(', ')
                ));

                $this->sendSummaryNotification(SummaryExpiringProtocols::class, $organisations);
            });
    }

    private function handleExpiredProtocols(Collection $organisations): void
    {
        $checkDate = today();

        logger()->info(sprintf(
            'ProcessProtocolsJob: checking for protocols expiring today (%s)...',
            $checkDate->format('Y-m-d')
        ));

        $organisations
            ->filter(fn (Organisation $organisation) => $checkDate->isSameDay($organisation->last_protocol_expires_at))
            ->each(function (Organisation $organisation) {
                $organisation->setInactive();

                $this->sendNotification(ExpiredProtocol::class, $organisation);
            })
            ->tap(function (Collection $organisations) {
                logger()->info(sprintf(
                    'ProcessProtocolsJob: found %d organisations with expired protocols: %s',
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
