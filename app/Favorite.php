<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model {

    use SoftDeletes;

    protected $fillable = ['user_id', 'drama_id', 'type', 'rating'];

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
