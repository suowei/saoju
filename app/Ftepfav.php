<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ftepfav extends Model
{
    protected $fillable = ['user_id', 'ftep_id', 'created_at'];

    public $timestamps = false;

    public function ftep()
    {
        return $this->belongsTo('App\Ftep');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
