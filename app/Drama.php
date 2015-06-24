<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Drama extends Model {

    use SoftDeletes;

	protected $fillable = ['title', 'alias', 'type', 'era', 'genre', 'original', 'count', 'state', 'sc', 'poster_url', 'introduction'];

    public function episodes()
    {
        return $this->hasMany('App\Episode');
    }

}
