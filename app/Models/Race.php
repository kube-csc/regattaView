<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    public function raceTabele()
    {
        return $this->belongsTo(Tabele::class, 'tabele_id');
    }
}
