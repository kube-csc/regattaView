<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class SportSection extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function getImagePathAttribute()
      {
          return Storage::disk('public')->url($this->bild);
      }
}
