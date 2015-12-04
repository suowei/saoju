<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Live extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'showtime', 'information', 'poster_url', 'record_url'];

    public function scopeMultiwhere($query, $arr)
    {
        foreach ($arr as $key => $value) {
            $query = $query->where($key, $value[0], $value[1]);
        }
        return $query;
    }
}
