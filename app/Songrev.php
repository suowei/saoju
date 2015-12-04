<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Songrev extends Model
{
    use SoftDeletes;

    protected $fillable = ['song_id', 'user_id', 'title', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function song()
    {
        return $this->belongsTo('App\Song');
    }
}
