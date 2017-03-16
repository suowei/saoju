<?php namespace App\Http\Controllers;

use App\Review;
use App\Drama;
use App\Episode;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Input;

class ReviewController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        if($request->has('type'))
            $type = $request->input('type');
        else
            $type = 0;
        if($type < 0)
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('reviews.visible', '<=', 1)
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->paginate(20);
        }
        else
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('dramas.type', '=', $type)
                ->where('reviews.visible', '<=', 1)
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->paginate(20);
        }
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['episode' => function($query)
        {
            $query->select('id', 'title');
        }]);
        return view('review.index')->with('type', $type)->withReviews($reviews);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        if($request->has('episode'))
            return view('review.create')->withDrama(Drama::find($request->input('drama'), ['id', 'title']))
                ->withEpisode(Episode::find($request->input('episode'), ['id', 'title']));
        else
            return view('review.create')->withDrama(Drama::find($request->input('drama'), ['id', 'title']));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
            'drama_id' => 'required',
            'content' => 'required',
            'visible' => 'required',
            'title' => 'max:255',
            'episode_id' => 'exists:episodes,id',
        ]);

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
            return redirect()->route('review.show', [$review]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $review = Review::find($id);
        if(!$review)
            return redirect()->to('/');
        $review->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $review->load(['drama' => function($query)
        {
            $query->select('id', 'title');
        }]);
        if($review->episode_id)
        {
            $review->load(['episode' => function($query)
            {
                $query->select('id', 'title');
            }]);
        }
        return view('review.show')->withReview($review);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $review = Review::find($id);
        if($review->user_id == Auth::id())
        {
            $review->load(['drama' => function($query)
            {
                $query->select('id', 'title');
            }]);
            if($review->episode_id)
            {
                $review->load(['episode' => function($query)
                {
                    $query->select('id', 'title');
                }]);
            }
            return view('review.edit')->withReview($review);
        }
        else
        {
            return redirect()->back();
        }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        $this->validate($request, [
            'content' => 'required',
            'visible' => 'required',
            'title' => 'max:255',
        ]);

        $review = Review::find($id);
        if($review->user_id != $request->user()->id)
            return redirect()->back()->withErrors('您不是发表该评论的用户，无法修改');

        $review->title = $request->input('title');
        $review->content = $request->input('content');
        $review->visible = $request->input('visible');

        if($review->save())
        {
            return redirect()->route('review.show', [$review]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$review = Review::find($id);
        if ($review->user_id == Auth::id())
        {
            if($review->delete())
            {
                DB::table('users')->where('id', $review->user_id)->decrement('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->decrement('reviews');
                if($review->episode_id)
                    DB::table('episodes')->where('id', $review->episode_id)->decrement('reviews');
            }
        }
        return redirect()->back();
	}

}
