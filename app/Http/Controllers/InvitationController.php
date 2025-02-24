<?php

namespace App\Http\Controllers;

use App\Models\EvenementSportif;
use App\Models\Invitation;
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
        $dejaInvite = Invitation::where('evenement_sportif_id', $evenement->id)
            ->where('invite_id', $request->invite_id)
            ->exists();

        if ($dejaInvite) {
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
     * Accepter une invitation.
     */
    public function accepter(Invitation $invitation)
    {
        // Vérifier que c’est bien l’invité connecté
        if (Auth::id() !== $invitation->invite_id) {
            abort(403);
        }

        $invitation->update(['statut' => 'accepté']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Invitation acceptée.');
    }

    /**
     * Refuser une invitation.
     */
    public function refuser(Invitation $invitation)
    {
        // Vérifier que c’est bien l’invité connecté
        if (Auth::id() !== $invitation->invite_id) {
            abort(403);
        }

        $invitation->update(['statut' => 'refusé']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Invitation refusée.');
    }
}
