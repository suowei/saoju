<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Epfav extends Model
{
    protected $fillable = ['user_id', 'episode_id', 'type', 'rating'];

    public function episode()
    {
        return $this->belongsTo('App\Episode');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
