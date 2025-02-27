<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use App\Events\MessageEnvoye;
use App\Models\EvenementSportif;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NouveauMessage;

class ChatEvenement extends Component
{
    public $evenementId;
    public $message;

    protected $rules = [
        'message' => 'required|string|max:500',
    ];

    // Écouteur Livewire pour rafraîchir la liste des messages
    protected $listeners = [
        'refreshChat' => 'refreshChatHandler'
    ];

    public function sendMessage()
    {
        $this->validate();
    
        $message = Message::create([
            'user_id'              => Auth::id(),
            'evenement_sportif_id' => $this->evenementId,
            'contenu'              => $this->message,
        ]);
    
        // 🔥 Diffuser l'événement en temps réel
        broadcast(new MessageEnvoye($message))->toOthers();
    
        // Notifier l'organisateur
        $evenement = EvenementSportif::find($this->evenementId);
        if ($evenement && $evenement->organisateur) {
            $evenement->organisateur->notify(new NouveauMessage($message));
        }

        \Log::info('🔴 MessageEnvoye broadcasté : ', ['message' => $message]);

        // Réinitialiser l'input du message
        $this->reset('message');

        // Rafraîchir le chat instantanément pour l'envoyeur
        $this->refreshChatHandler();
    }

    public function refreshChatHandler()
    {
        \Log::info("🔄 Livewire a bien reçu l'événement refreshChat !");
        $this->render();
    }

    public function render()
    {
        return view('livewire.chat-evenement', [
            'messages' => Message::where('evenement_sportif_id', $this->evenementId)
                ->orderBy('created_at', 'asc') // Afficher les messages du plus ancien au plus récent
                ->get()
        ]);
    }
}
