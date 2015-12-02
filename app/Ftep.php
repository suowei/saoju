<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ftep extends Model
{
    use SoftDeletes;

    protected $fillable = ['ft_id', 'title', 'release_date', 'url', 'staff', 'poster_url'];

    public function ft()
    {
        return $this->belongsTo('App\Ft');
    }
}
