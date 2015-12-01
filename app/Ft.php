<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ft extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'host', 'poster_url', 'introduction'];

    public function fteps()
    {
        return $this->hasMany('App\Ftep');
    }

    public function scopeMultiwhere($query, $arr)
    {
        foreach ($arr as $key => $value) {
            $query = $query->where($key, $value[0], $value[1]);
        }
        return $query;
    }
}
