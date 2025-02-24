<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evenement_sportif_id',
        'inviteur_id',
        'invite_id',
        'statut'
    ];

    public function evenement()
    {
        return $this->belongsTo(EvenementSportif::class, 'evenement_sportif_id');
    }

    public function inviteur()
    {
        return $this->belongsTo(User::class, 'inviteur_id');
    }

    public function invite()
    {
        return $this->belongsTo(User::class, 'invite_id');
    }
}
