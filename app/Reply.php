<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model {

    use SoftDeletes;

    protected $fillable = ['review_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
