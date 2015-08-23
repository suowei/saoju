<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clubver extends Model
{
    protected $fillable = ['club_id', 'user_id', 'first', 'name', 'information'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
