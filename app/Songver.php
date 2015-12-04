<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Songver extends Model
{
    protected $fillable = ['song_id', 'user_id', 'first', 'title', 'alias',
        'artist', 'url', 'poster_url', 'staff', 'lyrics'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
