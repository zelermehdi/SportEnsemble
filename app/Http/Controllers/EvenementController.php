<?php

namespace App\Http\Controllers;

use App\Models\EvenementSportif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Liste des événements avec filtres.
     */
    public function index(Request $request)
    {
        $query = EvenementSportif::query();

        // Recherche par titre
        if ($request->filled('q')) {
            $query->where('titre', 'LIKE', '%' . $request->q . '%');
        }

        // Filtre par type de sport
        if ($request->filled('type_sport') && $request->type_sport !== 'all') {
            $query->where('type_sport', $request->type_sport);
        }

        // Filtre par lieu
        if ($request->filled('lieu')) {
            $query->where('lieu', 'LIKE', '%' . $request->lieu . '%');
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Tri du plus récent au plus ancien
        $evenements = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('evenements.index', compact('evenements'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('evenements.create');
    }

    /**
     * Enregistrer un nouvel événement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type_sport'       => 'required|in:foot,course,basket,autre',
            'lieu'             => 'required|string|max:255',
            'date'             => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            // Champs supplémentaires
            'niveau'           => 'required|in:débutant,amateur,pro',
            'tags'             => 'nullable|string|max:255',
        ]);
    
        EvenementSportif::create([
            'titre'            => $request->titre,
            'description'      => $request->description,
            'type_sport'       => $request->type_sport,
            'lieu'             => $request->lieu,
            'date'             => $request->date,
            'max_participants' => $request->max_participants,
            'statut'           => 'ouvert',
            'user_id'          => auth()->id(),
            // Champs supplémentaires
            'niveau'           => $request->niveau,
            'tags'      =>       $request->tags,
        ]);
    
        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès.');
    }
    

    /**
     * Afficher un événement (le détail).
     */
    public function show(EvenementSportif $evenement)
    {
        // on peut charger les participations et messages
        $evenement->load('participations.user', 'messages.user');

        return view('evenements.show', compact('evenement'));
    }
}
