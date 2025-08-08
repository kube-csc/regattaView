<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RaceType;

class RegattaTeam extends Model
{
    use HasFactory;

    public function teamWertungsGruppe()
    {
        return $this->belongsTo(RaceType::class, 'gruppe_id');
    }

    public function lanes()
    {
        return $this->hasMany(Lane::class, 'mannschaft_id');
    }
    // Bild-Accessor für Präsentation
    public function getBildUrlAttribute()
    {
        if ($this->bild) {
            return asset('storage/' . $this->bild);
        }
        return null;
    }
}
