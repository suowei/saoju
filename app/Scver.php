<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scver extends Model
{
    protected $fillable = ['sc_id', 'user_id', 'first', 'name', 'alias', 'club_id', 'jobs', 'weibo', 'information'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function club()
    {
        return $this->belongsTo('App\Club');
    }
}
