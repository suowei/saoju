<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Screv extends Model
{
    use SoftDeletes;

    protected $fillable = ['model', 'model_id', 'user_id', 'title', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
