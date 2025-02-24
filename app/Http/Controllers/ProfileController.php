<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de modification du profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation des champs
        $request->validate([
            'name'            => 'required|string|max:255',
            'avatar'          => 'nullable|image|max:2048', // si vous gérez l'upload d'image
            'bio'             => 'nullable|string',
            'ville'           => 'nullable|string|max:255',
            'sports_favoris'  => 'nullable|string|max:255',
        ]);

        // Mise à jour du nom
        $user->name = $request->name;

        // Upload de l’avatar (facultatif)
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path; // ex: "avatars/filename.jpg"
        }

        // Mise à jour du reste
        $user->bio = $request->bio;
        $user->ville = $request->ville;
        $user->sports_favoris = $request->sports_favoris;

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}
