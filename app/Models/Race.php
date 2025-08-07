<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    public function raceTabele()
    {
        return $this->belongsTo(Tabele::class, 'tabele_id');
    }

    public function lanes()
    {
        return $this->hasMany(Lane::class, 'rennen_id');
    }
}
