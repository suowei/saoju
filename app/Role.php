<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['drama_id', 'episode_id', 'sc_id', 'job', 'note', 'user_id'];

    public function sc()
    {
        return $this->belongsTo('App\Sc');
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
