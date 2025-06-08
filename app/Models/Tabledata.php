<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabledata extends Model
{
    public function getRaceTable()
    {
        return $this->belongsTo(Tabele::class, 'tabele_id');
    }

    public function getMannschaft()
    {
        return $this->belongsTo(RegattaTeam::class, 'mannschaft_id');
    }
}
