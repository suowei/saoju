<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ftrev extends Model
{
    use SoftDeletes;

    protected $fillable = ['ft_id', 'ftep_id', 'user_id', 'title', 'content'];

    public function ft()
    {
        return $this->belongsTo('App\Ft');
    }

    public function ftep()
    {
        return $this->belongsTo('App\Ftep');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
