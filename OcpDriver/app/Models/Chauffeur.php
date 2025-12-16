<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Chauffeur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'cin',
        'status',
    ];

    /**
     * Hidden fields in API responses.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts for fields.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relations
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'chauffeur_id');
    }
}
