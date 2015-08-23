<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dramaver extends Model
{
    protected $fillable = ['drama_id', 'user_id', 'first',
        'title', 'alias', 'type', 'era', 'genre', 'original', 'count', 'state', 'sc', 'poster_url', 'introduction'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }
}
