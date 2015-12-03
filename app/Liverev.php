<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Liverev extends Model
{
    use SoftDeletes;

    protected $fillable = ['live_id', 'user_id', 'title', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function live()
    {
        return $this->belongsTo('App\Live');
    }
}
