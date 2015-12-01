<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ftepver extends Model
{
    protected $fillable = ['ftep_id', 'user_id', 'first', 'title', 'release_date', 'url', 'staff', 'poster_url'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ftep()
    {
        return $this->belongsTo('App\Ftep');
    }
}
