<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EvenementSportif;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendrierEvenements extends Component
{
    public $evenements = [];

    public function mount()
    {
        $user = Auth::user();

        // Récupérer les événements que l'utilisateur a créés
        $created = $user->evenementsSportifs()->get();

        // Récupérer les événements auxquels l'utilisateur participe
        // On suppose que la relation "participations" est définie sur le modèle User
        // et que chaque participation possède une relation "evenement" qui renvoie l'événement.
        $participated = $user->participations()->with('evenement')->get()
            ->pluck('evenement')
            ->filter(); // pour enlever les éventuels null

        // Fusionner les deux collections et supprimer les doublons (même événement créé et auquel on participe)
        $allEvents = $created->merge($participated)->unique('id');

        // Transformer la collection en tableau au format attendu par le calendrier
        $this->evenements = $allEvents->map(function ($event) {
            return [
                'title' => $event->titre,
                // Format ISO8601 pour que FullCalendar reconnaisse la date correctement
                'start' => Carbon::parse($event->date)->toIso8601String(),
                'url'   => route('evenements.show', $event),
                'color' => $event->statut === 'complet' ? 'red' : 'green',
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.calendrier-evenements');
    }
}
