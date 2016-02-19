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
            ->where('visible', 1)
            ->select('id', 'drama_id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        return $reviews;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'drama_id' => 'required',
            'content' => 'required',
            'visible' => 'required',
            'title' => 'max:255',
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
        $review->visible = $request->input('visible');
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required',
            'visible' => 'required',
            'title' => 'max:255',
        ]);

        $review = Review::find($id);
        if($review->user_id != $request->user()->id)
            return response('非评论用户', 422);

        $review->title = $request->input('title');
        $review->content = $request->input('content');
        $review->visible = $request->input('visible');

        if($review->save())
        {
            return ['result' => 'success'];
        }
        else
        {
            return response('修改失败', 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);
        if ($review->user_id == $request->user()->id)
        {
            if($review->delete())
            {
                DB::table('users')->where('id', $review->user_id)->decrement('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->decrement('reviews');
                if($review->episode_id)
                    DB::table('episodes')->where('id', $review->episode_id)->decrement('reviews');
                return ['result' => 'success'];
            }
        }
        return response('非评论用户', 422);
    }
}
