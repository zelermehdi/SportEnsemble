<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Ajoute cette ligne
        'evenement_sportif_id',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evenement()
    {
        return $this->belongsTo(EvenementSportif::class, 'evenement_sportif_id');
    }
}
