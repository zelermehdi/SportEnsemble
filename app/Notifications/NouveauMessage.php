<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Message;

class NouveauMessage extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // Stockage en base de données
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Nouveau message dans l'événement : " . $this->message->evenement->titre,
            'evenement_id' => $this->message->evenement_sportif_id,
        ];
    }
}
