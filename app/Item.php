<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['list_id', 'no', 'drama_id', 'episode_id', 'review'];

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

    public function episode()
    {
        return $this->belongsTo('App\Episode');
    }
}
