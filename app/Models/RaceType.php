<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceType extends Model
{
    public function template()
    {
        return $this->belongsTo(RaceTypeTemplate::class, 'race_type_template_id');
    }
}
