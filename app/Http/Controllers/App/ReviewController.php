<?php namespace App\Http\Controllers\App;

use App\Review;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ReviewController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        }, 'user' => function($query)
        {
            $query->select('id','name');
        }, 'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->orderBy('id', 'desc')->simplePaginate(20);
        return $reviews;
    }
}
