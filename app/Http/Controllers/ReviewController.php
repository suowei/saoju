<?php namespace App\Http\Controllers;

use App\Reply;
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
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->paginate(20);
        }
        else
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('dramas.type', '=', $type)
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
        return view('review.create')
            ->withDrama(Drama::find($request->input('drama')))
            ->withEpisode(Episode::find($request->input('episode')));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
            'content' => 'required',
            'title' => 'max:255',
            'user_id' => 'required|exists:users,id',
            'drama_id' => 'required|exists:dramas,id',
            'episode_id' => 'exists:episodes,id',
        ]);

        if($review = Review::create(Input::all()))
        {
            DB::table('users')->where('id', $review->user_id)->increment('reviews');
            DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
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
        $replies = Reply::where('review_id', $id)->get();
        return view('review.show')->withReview($review)->withReplies($replies);
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
            'title' => 'max:255',
        ]);

        $review = Review::find($id);
        $review->title = $request->input('title');
        $review->content = $request->input('content');

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
                DB::table('episodes')->where('id', $review->episode_id)->decrement('reviews');
                Reply::where('review_id', $id)->delete();
            }
        }
        return redirect()->back();
	}

}
