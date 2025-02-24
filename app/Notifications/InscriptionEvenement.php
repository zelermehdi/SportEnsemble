<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EvenementSportif;

class InscriptionEvenement extends Notification
{
    use Queueable;

    protected $evenement;

    public function __construct(EvenementSportif $evenement)
    {
        $this->evenement = $evenement;
    }

    public function via($notifiable)
    {
        return ['database']; // Stockage en base de données
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Un nouvel utilisateur s'est inscrit à l'événement : " . $this->evenement->titre,
            'evenement_id' => $this->evenement->id,
        ];
    }
}
