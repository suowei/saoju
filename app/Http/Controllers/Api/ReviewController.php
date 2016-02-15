<?php namespace App\Http\Controllers\Api;

use App\Review;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB, Validator;

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
            ->simplePaginate(10);
        return $reviews;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'title' => 'max:255',
            'drama_id' => 'required',
            'episode_id' => 'exists:episodes,id',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

        $review = new Review;
        $review->user_id = $request->user()->id;
        $review->drama_id = $request->input('drama_id');
        if ($request->has('episode_id'))
            $review->episode_id = $request->input('episode_id');
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        if($review->save())
        {
            DB::table('users')->where('id', $review->user_id)->increment('reviews');
            DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
            if($review->episode_id)
                DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
            return ['result' => 'success'];
        }
        else
        {
            return response('添加失败', 422);
        }
    }
}
