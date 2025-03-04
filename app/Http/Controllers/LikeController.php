<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Photo $photo)
    {
        $like = Like::where('user_id', auth()->id())->where('photo_id', $photo->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create(['user_id' => auth()->id(), 'photo_id' => $photo->id]);
        }

        return back();
    }
}
