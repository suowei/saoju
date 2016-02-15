<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model {

	use SoftDeletes;

    protected $fillable = ['drama_id', 'episode_id', 'user_id', 'title', 'content', 'visible'];

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

    public function episode()
    {
        return $this->belongsTo('App\Episode');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
