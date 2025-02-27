<?php

namespace App\Http\Controllers;

use App\Models\EvenementSportif;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Affiche la liste des événements pour l’admin.
     */
    public function index()
    {
        $evenements = EvenementSportif::all();

        return view('admin.index', compact('evenements'));
    }

    /**
     * Supprime un événement.
     */
    public function supprimerEvenement(EvenementSportif $evenement)
    {
        if (auth()->user()->id !== $evenement->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Vous n’êtes pas autorisé à supprimer cet événement.');
        }
    
        $evenement->delete();
        return redirect()->route('admin.index')->with('success', 'Événement supprimé avec succès.');
    }
    
}
