<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\EvenementSportif;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        Log::info('🚀 Notification envoyée : Inscription à un événement !', [
            'user_id' => $notifiable->id,
            'evenement' => $this->evenement->titre,
        ]);



        return ['database','broadcast']; // Stockage en base de données
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Un nouvel utilisateur s'est inscrit à l'événement : " . $this->evenement->titre,
            'evenement_id' => $this->evenement->id,
        ];
    }
}
