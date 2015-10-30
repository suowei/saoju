<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {

    protected $fillable = ['user_id', 'drama_id', 'type', 'rating'];

    public function drama()
    {
        return $this->belongsTo('App\Drama');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'drama_id', 'drama_id');
    }

}
