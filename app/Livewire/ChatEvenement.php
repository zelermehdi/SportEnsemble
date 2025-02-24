<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\EvenementSportif;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NouveauMessage;

class ChatEvenement extends Component
{
    public $evenementId;
    public $message;

    protected $rules = [
        'message' => 'required|string|max:500',
    ];

    protected $listeners = ['messageEnvoye' => '$refresh'];

    public function sendMessage()
    {
        $this->validate();

        $message = Message::create([
            'user_id'              => Auth::id(),
            'evenement_sportif_id' => $this->evenementId,
            'contenu'              => $this->message,
        ]);

        // Notifier l'organisateur de l'événement
        $evenement = EvenementSportif::find($this->evenementId);
        if ($evenement && $evenement->organisateur) {
            $evenement->organisateur->notify(new NouveauMessage($message));
        }

        $this->reset('message');
        $this->dispatch('messageEnvoye');
    }

    public function render()
    {
        $evenement = EvenementSportif::find($this->evenementId);

        return view('livewire.chat-evenement', [
            'messages' => Message::where('evenement_sportif_id', $this->evenementId)
                ->orderBy('created_at', 'desc')
                ->get(),
            'evenement' => $evenement
        ]);
    }
}
