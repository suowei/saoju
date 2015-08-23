<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodever extends Model
{
    protected $fillable = ['episode_id', 'user_id', 'first',
        'title', 'alias', 'release_date', 'url', 'sc', 'duration', 'poster_url', 'introduction'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
