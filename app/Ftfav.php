<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ftfav extends Model
{
    protected $fillable = ['user_id', 'ft_id', 'created_at'];

    public $timestamps = false;

    public function ft()
    {
        return $this->belongsTo('App\Ft');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
