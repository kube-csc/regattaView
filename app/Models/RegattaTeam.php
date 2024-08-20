<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegattaTeam extends Model
{
    public function teamWertungsGruppe()
    {
        return $this->belongsTo(RaceType::class, 'gruppe_id');
    }
}
