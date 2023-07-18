<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Filament\Resources\DocumentResource;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class SummaryExpiredProtocols extends Notification implements ShouldQueue
{
    use Queueable;

    public Collection $organisations;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $organisations)
    {
        $this->organisations = $organisations;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('email.summary_expired_protocols.subject', [
                'count' => $this->organisations->count(),
            ]))
            ->line(__('email.summary_expired_protocols.subject', [
                'count' => $this->organisations->count(),
            ]))
            ->action(__('email.summary_expired_protocols.view'), static::actionUrl())
            ->markdown('emails.summary', [
                'organisations' => $this->organisations->pluck('name'),
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('email.summary_expired_protocols.subject', [
                'count' => $this->organisations->count(),
            ]))
            ->actions([
                Action::make('view')
                    ->label(__('email.summary_expired_protocols.view'))
                    ->url(static::actionUrl()),
            ])
            ->getDatabaseMessage();
    }

    protected static function actionUrl(?Carbon $date = null): string
    {
        $date ??= today();

        return DocumentResource::getUrl('index', [
            'tableFilters' => [
                'expires_at' => [
                    'date_from' => $date->toDateTimeString(),
                    'date_until' => $date->toDateTimeString(),
                ],
            ],
        ]);
    }
}
