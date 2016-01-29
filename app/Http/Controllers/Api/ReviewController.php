<?php namespace App\Http\Controllers\Api;

use App\Review;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ReviewController extends Controller {

    public function __construct()
    {
        $this->middleware('apiauth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        },
            'user' => function($query)
        {
            $query->select('id','name');
        },
            'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'drama_id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->orderBy('id', 'desc')
            ->simplePaginate(15);
        return $reviews;
    }
}
