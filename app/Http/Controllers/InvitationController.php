<?php

namespace App\Http\Controllers;

use App\Models\EvenementSportif;
use App\Models\Invitation;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Inviter un utilisateur à un événement.
     */
    public function inviter(Request $request, EvenementSportif $evenement)
    {
        $request->validate([
            'invite_id' => 'required|exists:users,id',
        ]);

        // Vérifier si l'utilisateur est déjà invité
        if (Invitation::where('evenement_sportif_id', $evenement->id)
            ->where('invite_id', $request->invite_id)
            ->exists()) {
            return back()->with('error', 'Cet utilisateur a déjà été invité.');
        }

        Invitation::create([
            'evenement_sportif_id' => $evenement->id,
            'inviteur_id'          => Auth::id(),
            'invite_id'            => $request->invite_id,
            'statut'               => 'en_attente',
        ]);

        return back()->with('success', 'Invitation envoyée avec succès.');
    }

    /**
     * Accepter une invitation et rejoindre l'événement.
     */
    public function accepter($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        // Vérifier que l'utilisateur authentifié est bien l'invité
        if (Auth::id() !== $invitation->invite_id) {
            abort(403);
        }

        // Ajouter l'utilisateur comme participant à l'événement
        Participation::create([
            'user_id'              => Auth::id(),
            'evenement_sportif_id' => $invitation->evenement_sportif_id,
            'statut'               => 'accepté',
        ]);

        // Supprimer l'invitation après acceptation
        $invitation->delete();

        return redirect()->route('dashboard')->with('success', 'Invitation acceptée.');
    }

    /**
     * Refuser une invitation et la supprimer.
     */
    public function refuser($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        // Vérifier que l'utilisateur authentifié est bien l'invité
        if (Auth::id() !== $invitation->invite_id) {
            abort(403);
        }

        // Supprimer l'invitation après refus
        $invitation->delete();

        return redirect()->route('dashboard')->with('success', 'Invitation refusée.');
    }
}
