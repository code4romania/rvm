<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Organisation;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringProtocol extends Notification implements ShouldQueue
{
    use Queueable;

    public Organisation $organisation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via = ['mail'];

        if ($notifiable instanceof User) {
            $via[] = 'database';
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('email.expiring_protocol.subject', [
                'name' => $this->organisation->name,
            ]))
            ->line(__('email.expiring_protocol.line_1', [
                'name' => $this->organisation->name,
            ]))
            ->line(__('email.expiring_protocol.line_2'));
        // ->action('Notification Action', url('/'))
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('email.expiring_protocol.subject', [
                'name' => $this->organisation->name,
            ]))
            ->body(__('email.expiring_protocol.line_2'))
            ->getDatabaseMessage();
    }
}
