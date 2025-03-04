<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvenementSportif;
use Illuminate\Support\Facades\Log;
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
        Log::info('🚀 Tentative de création d’un événement', ['user_id' => auth()->id()]);
    
        // 🔍 Log des données reçues
        Log::info('📝 Données reçues', $request->all());
    
        // Validation des champs
        $request->validate([
            'titre' => 'required|string|max:255',
            'type_sport' => 'required|string',
            'lieu' => 'required|string',
            'date' => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
    
        Log::info('✅ Validation réussie');
    
        try {
            // Création de l'événement
            $evenement = EvenementSportif::create([
                'titre' => $request->titre,
                'type_sport' => $request->type_sport,
                'lieu' => $request->lieu,
                'date' => $request->date,
                'max_participants' => $request->max_participants,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'user_id' => auth()->id(),
            ]);
    
            Log::info('🎉 Événement créé avec succès', ['id' => $evenement->id]);
    
            return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès !');
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la création', ['message' => $e->getMessage()]);
            return back()->withErrors('Erreur lors de la création de l’événement.');
        }
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


    public function map()
    {
        $evenements = EvenementSportif::whereNotNull('latitude')
                         ->whereNotNull('longitude')
                         ->get();
    
       
    
        return view('evenements.map', compact('evenements'));
    }
}
