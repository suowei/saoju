<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model {

	protected $fillable = ['user_id', 'model', 'model_id', 'type', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
