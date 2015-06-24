<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Episode extends Model {

    use SoftDeletes;

    protected $fillable = ['drama_id', 'title', 'alias', 'release_date', 'url', 'sc', 'duration', 'poster_url', 'introduction'];

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

}
