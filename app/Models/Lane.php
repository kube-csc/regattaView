<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
    public function regattaTeam()
    {
        return $this->belongsTo(RegattaTeam::class, 'mannschaft_id');
    }
}
