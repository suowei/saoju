<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ftver extends Model
{
    protected $fillable = ['ft_id', 'user_id', 'first', 'title', 'host', 'poster_url', 'introduction'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ft()
    {
        return $this->belongsTo('App\Ft');
    }
}
