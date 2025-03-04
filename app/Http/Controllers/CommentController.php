<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        // Validation
        $request->validate([
            'contenu' => 'required|string|max:500',
        ]);

        // Ajouter le commentaire
        Comment::create([
            'user_id' => auth()->id(),
            'photo_id' => $photo->id,
            'contenu' => $request->contenu
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }
}
