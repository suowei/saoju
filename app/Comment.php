<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

    use SoftDeletes;

    protected $fillable = ['topic_id', 'user_id', 'ip', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
