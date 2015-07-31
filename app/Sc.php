<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sc extends Model
{
    protected $fillable = ['name', 'alias', 'club_id', 'jobs', 'information', 'user_id'];

    public function club()
    {
        return $this->belongsTo('App\Club');
    }
}
