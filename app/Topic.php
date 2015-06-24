<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model {

    use SoftDeletes;

    protected $fillable = ['title', 'user_id', 'ip', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
