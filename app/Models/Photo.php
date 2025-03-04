<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'evenement_sportif_id',
        'user_id',
        'path'
    ];

    public function evenement()
    {
        return $this->belongsTo(EvenementSportif::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
    
    public function comments()
{
    return $this->hasMany(Comment::class, 'photo_id');
}





}
