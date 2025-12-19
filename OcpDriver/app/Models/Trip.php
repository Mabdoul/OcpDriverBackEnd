<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    // Table name different from model
    protected $table = 'orders';

 protected $fillable = [
    'user_id',
    'chauffeur_id',
    'start_lat',
    'start_lng',
    'end_lat',
    'end_lng',
    'status'
];


    public function client() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chauffeur() {
        return $this->belongsTo(Chauffeur::class, 'chauffeur_id');
    }
}
