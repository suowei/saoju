<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listfav extends Model
{
    protected $fillable = ['user_id', 'list_id', 'created_at'];

    public $timestamps = false;

    public function dramalist()
    {
        return $this->belongsTo('App\Dramalist', 'list_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
