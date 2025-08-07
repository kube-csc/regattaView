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
}
