<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    
        return view('dashboard', compact('evenementsOrganises', 'evenementsParticipes'));
    }
    
}
