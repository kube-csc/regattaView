<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public function timevon()
    {
        return \Carbon\Carbon::createFromTimeString($this->time)->format('g:i a')
}
}
