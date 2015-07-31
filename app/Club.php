<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = ['name', 'information', 'user_id'];

    public function scs()
    {
        return $this->hasMany('App\Sc');
    }
}
