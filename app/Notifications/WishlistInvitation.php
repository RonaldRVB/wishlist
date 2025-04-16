<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WishlistInvitation extends Notification
{
    use Queueable;

    public function __construct(
        public string $eventTitle,
        public string $acceptUrl,
        public string $refuseUrl
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Invitation à l’événement : {$this->eventTitle}")
            ->markdown('emails.invitation', [
                'eventTitle' => $this->eventTitle,
                'acceptUrl' => $this->acceptUrl,
                'refuseUrl' => $this->refuseUrl,
            ]);
    }
}
