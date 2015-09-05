<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Dramalist extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    protected $fillable = ['user_id', 'title', 'introduction'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
