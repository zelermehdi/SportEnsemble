<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvenementSportif; // Importer le modèle

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Récupérer les événements organisés et ceux auxquels l'utilisateur participe
        $evenementsOrganises  = $user->evenementsSportifs ?? collect([]);
        $evenementsParticipes = $user->participations
            ? $user->participations->map->evenement
            : collect([]);

        // Récupérer **tous les événements** pour les afficher sur la carte
        $tousLesEvenements = EvenementSportif::all();

        return view('dashboard', compact('evenementsOrganises', 'evenementsParticipes', 'tousLesEvenements'));
    }
}
