<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ftep extends Model
{
    use SoftDeletes;

    protected $fillable = ['ft_id', 'title', 'release_date', 'url', 'staff', 'poster_url'];

    public function ft()
    {
        return $this->belongsTo('App\Ft');
    }
}
