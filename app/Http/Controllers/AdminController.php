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
        $evenement->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Événement supprimé avec succès.');
    }
}
