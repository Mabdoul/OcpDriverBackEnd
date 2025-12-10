<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
   public function client() {
    return $this->belongsTo(User::class, 'user_id');
}

public function chauffeur() {
    return $this->belongsTo(Chauffeur::class, 'chauffeur_id');
}

protected $fillable = [
    'user_id',
    'chauffeur_id',
    'pickup_lat',
    'pickup_lng',
    'drop_lat',
    'drop_lng',
    'status'
];

}
