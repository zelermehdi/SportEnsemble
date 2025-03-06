<?php

namespace App\Events;

use App\Models\EvenementSportif;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EvenementCree implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $evenement;

    public function __construct(EvenementSportif $evenement)
    {
        $this->evenement = $evenement;
        Log::info("📡 Diffusion de l'événement sur Pusher", ['id' => $evenement->id, 'titre' => $evenement->titre]);

    }

    public function broadcastOn()
    {
        return new Channel('evenements');
    }

    public function broadcastAs()
    {
        return 'EvenementCree';
    }
}
