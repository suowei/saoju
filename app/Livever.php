<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livever extends Model
{
    protected $fillable = ['live_id', 'user_id', 'first', 'title', 'showtime', 'information', 'poster_url', 'record_url'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
