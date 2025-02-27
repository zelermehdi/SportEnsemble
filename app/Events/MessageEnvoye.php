<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ✅ Vérifie cet import
use Illuminate\Queue\SerializesModels;

class MessageEnvoye implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat-evenement.' . $this->message->evenement_sportif_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'user' => $this->message->user->name,
            'contenu' => $this->message->contenu,
            'created_at' => $this->message->created_at->format('d/m/Y H:i'),
        ];
    }

    public function broadcastAs()
    {
        return 'MessageEnvoye';
    }
}
