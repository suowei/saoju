<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Drama extends Model {

    use SoftDeletes;

	protected $fillable = ['title', 'alias', 'type', 'era', 'genre', 'original', 'author', 'count', 'state', 'sc', 'poster_url', 'introduction'];

    public function episodes()
    {
        return $this->hasMany('App\Episode');
    }

    public function scopeMultiwhere($query, $arr)
    {
        foreach ($arr as $key => $value) {
            $query = $query->where($key, $value[0], $value[1]);
        }
        return $query;
    }

}
