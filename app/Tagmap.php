<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagmap extends Model
{
    public $timestamps = false;

    protected $fillable = ['drama_id', 'user_id', 'tag_id'];

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }
}
