<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        // Champs supplémentaires
        'avatar',
        'bio',
        'ville',
        'sports_favoris',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public function participations()
    {
        return $this->hasMany(Participation::class, 'user_id');
    }


    public function isAdmin()
{
    return $this->role === 'admin';
}

public function invitationsRecues()
{
    return $this->hasMany(Invitation::class, 'invite_id');
}


public function evenementsSportifs()
{
    return $this->hasMany(EvenementSportif::class, 'user_id');
}


}
