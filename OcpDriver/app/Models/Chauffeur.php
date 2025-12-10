<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Chauffeur extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'full_name', 'email', 'password', 'phone', 'cin', 'status'
    ];

    protected $hidden = [
        'password'
    ];

    // Relation
    public function orders() {
        return $this->hasMany(Order::class, 'chauffeur_id');
    }

    // JWT
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}

