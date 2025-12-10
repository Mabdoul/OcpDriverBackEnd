<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
     * Fields hidden in API responses.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts for attributes.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relations (Add later: trips)
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
