<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\EvenementSportif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(EvenementSportif $evenement)
    {
        $photos = $evenement->photos()->latest()->get();
        return view('photos.index', compact('evenement', 'photos'));
    }

    public function create(EvenementSportif $evenement)
    {
        return view('photos.create', compact('evenement'));
    }

    public function store(Request $request, EvenementSportif $evenement)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // 2Mo max
        ]);

        // Stockage
        $path = $request->file('photo')->store('photos', 'public');

        // Création en base
        Photo::create([
            'evenement_sportif_id' => $evenement->id,
            'user_id' => auth()->id(),
            'path' => $path
        ]);

        return redirect()->route('photos.index', $evenement)
            ->with('success', 'Photo ajoutée !');
    }



    public function destroy(Photo $photo)
    {
        // Vérifier si l'utilisateur est bien celui qui a ajouté la photo ou l'organisateur de l'événement
        if (auth()->id() == $photo->user_id || auth()->id() == $photo->evenement->user_id) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            return back()->with('success', 'Photo supprimée avec succès.');
        }
    
        return back()->with('error', 'Vous ne pouvez pas supprimer cette photo.');
    }
    


}
