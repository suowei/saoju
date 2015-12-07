<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ed extends Model
{
    protected $fillable = ['drama_id', 'episode_id', 'song_id', 'user_id'];

    public function song()
    {
        return $this->belongsTo('App\Song');
    }

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

    public function episode()
    {
        return $this->belongsTo('App\Episode');
    }
}
