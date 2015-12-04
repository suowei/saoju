<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livefav extends Model
{
    protected $fillable = ['user_id', 'live_id', 'created_at'];

    public $timestamps = false;

    public function live()
    {
        return $this->belongsTo('App\Live');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
