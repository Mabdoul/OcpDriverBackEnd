<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chauffeur extends Model
{
    public function orders() {
    return $this->hasMany(Order::class, 'chauffeur_id');
}

}
