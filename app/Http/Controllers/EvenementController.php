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
        Log::info('📝 Données reçues', $request->all());

        // Validation avec messages personnalisés
        $request->validate([
            'titre' => 'required|string|max:255',
            'type_sport' => 'required|string',
            'lieu' => 'required|string',
            'date' => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'statut' => 'required|in:ouvert,fermé,complet',
        ], [
            'titre.required' => 'Le titre de l’événement est obligatoire.',
            'type_sport.required' => 'Veuillez sélectionner un type de sport.',
            'lieu.required' => 'Le lieu est requis.',
            'date.required' => 'Veuillez choisir une date pour l’événement.',
            'statut.required' => 'Veuillez définir le statut de l’événement.',
            'max_participants.min' => 'Le nombre de participants doit être au moins 1.',
        ]);

        Log::info('✅ Validation réussie');

        try {
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
                'statut' => $request->statut,
            ]);

            Log::info('🎉 Événement créé avec succès', ['id' => $evenement->id]);

            return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès !');
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la création', ['message' => $e->getMessage()]);
            return back()->withErrors('Erreur lors de la création de l’événement.');
        }
    }


    /**
     * Afficher un événement (détail).
     */
    public function show(EvenementSportif $evenement)
    {
        $evenement->load('participations.user', 'messages.user');
        return view('evenements.show', compact('evenement'));
    }

    /**
     * Afficher la carte des événements.
     */
    public function map()
    {
        $evenements = EvenementSportif::whereNotNull('latitude')->whereNotNull('longitude')->get();
        return view('evenements.map', compact('evenements'));
    }

    /**
     * Modifier un événement.
     */
    public function update(Request $request, EvenementSportif $evenement)
    {
        
        // Vérification que l'utilisateur est l'organisateur
        if (auth()->id() !== $evenement->user_id) {
            abort(403, "Vous n'êtes pas autorisé à modifier cet événement.");
        }
        
        // Si l'événement est complet, empêcher toute modification du statut
        if ($evenement->statut === 'complet') {
            return redirect()->back()->withErrors("L'événement est complet et ne peut plus être modifié.");
        }
    
        // Validation du statut
        $data = $request->validate([
            'statut' => 'required|in:ouvert,fermé,complet',
        ], [
            'statut.required' => 'Veuillez définir le statut de l’événement.',
            'statut.in' => 'Le statut choisi est invalide.',
        ]);
    
        // Mise à jour du statut
        $evenement->update([
            'statut' => $data['statut']
        ]);
    
        return redirect()->route('evenements.index')->with('success', 'Statut de l’événement mis à jour avec succès.');
    }
    


    // /**
    //  * Annuler un événement.
    //  */
    public function annuler(EvenementSportif $evenement)
    {
        // Vérification que l'utilisateur est l'organisateur
        if (auth()->id() !== $evenement->user_id) {
            abort(403, "Vous n'êtes pas autorisé à annuler cet événement.");
        }
    
        // Annulation de l'événement en mettant le statut à 'fermé'
        $evenement->update(['statut' => 'fermé']);
    
        return redirect()->route('evenements.index')->with('success', 'Événement annulé avec succès.');
    }
    
    public function edit(EvenementSportif $evenement)
{
    // Vérifier que l'utilisateur est l'organisateur de l'événement
    if (auth()->id() !== $evenement->user_id) {
        abort(403, "Vous n'êtes pas autorisé à modifier cet événement.");
    }
    
    // Retourner la vue d'édition avec l'événement concerné
    return view('evenements.edit', compact('evenement'));
}

    

}
