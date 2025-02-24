<?php

namespace App\Http\Controllers;

use App\Models\EvenementSportif;
use App\Models\Participation;
use App\Notifications\InscriptionEvenement;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    /**
     * S'inscrire à un événement.
     */
    public function participer(EvenementSportif $evenement)
    {
        // Vérifier si l'utilisateur est déjà inscrit
        $dejaInscrit = Participation::where('user_id', Auth::id())
            ->where('evenement_sportif_id', $evenement->id)
            ->exists();

        if ($dejaInscrit) {
            return back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Vérifier s'il reste de la place
        if ($evenement->max_participants 
            && $evenement->participations()->count() >= $evenement->max_participants
        ) {
            return back()->with('error', 'L’événement est déjà complet.');
        }

        // Ajouter la participation
        $participation = Participation::create([
            'user_id'             => Auth::id(),
            'evenement_sportif_id' => $evenement->id,
            'statut'              => 'accepté',
        ]);

        // Notifier l'organisateur
        if ($evenement->organisateur) {
            $evenement->organisateur->notify(new InscriptionEvenement($evenement));
        }

        return back()->with('success', 'Inscription réussie.');
    }

    /**
     * Se retirer / annuler sa participation.
     */
    public function seRetirer(EvenementSportif $evenement)
    {
        Participation::where('user_id', Auth::id())
            ->where('evenement_sportif_id', $evenement->id)
            ->delete();

        return back()->with('success', 'Vous avez annulé votre participation.');
    }
}
