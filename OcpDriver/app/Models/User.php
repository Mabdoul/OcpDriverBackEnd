<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relations
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
