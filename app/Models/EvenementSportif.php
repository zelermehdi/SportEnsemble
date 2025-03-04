<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementSportif extends Model
{
    use HasFactory;

    protected $table = 'evenements_sportifs'; 

    protected $fillable = [
        'titre',
        'description',
        'type_sport',
        'user_id',
        'lieu',
        'date',
        'max_participants',
        'statut',
        'niveau',
        'tags',
        'latitude',   
        'longitude',  
    ];




public function participations()
{
    return $this->hasMany(Participation::class, 'evenement_sportif_id');
}



public function messages()
{
    return $this->hasMany(Message::class, 'evenement_sportif_id');
}

public function organisateur()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function photos()
{
    return $this->hasMany(Photo::class, 'evenement_sportif_id');
}



}