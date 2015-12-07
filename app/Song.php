<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'alias', 'artist', 'url', 'poster_url', 'staff', 'lyrics'];

    public function scopeMultiwhere($query, $arr)
    {
        foreach ($arr as $key => $value) {
            $query = $query->where($key, $value[0], $value[1]);
        }
        return $query;
    }
}
