<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Songfav extends Model
{
    protected $fillable = ['user_id', 'song_id', 'created_at'];

    public $timestamps = false;

    public function song()
    {
        return $this->belongsTo('App\Song');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
