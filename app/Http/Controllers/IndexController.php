<?php namespace App\Http\Controllers;

use App\Drama;
use App\Episode;
use App\History;
use App\Review;

use Illuminate\Http\Request;

class IndexController extends Controller {

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
            $episodes = Episode::join('dramas', function($join)
            {
                $join->on('episodes.drama_id', '=', 'dramas.id')
                    ->where('episodes.release_date', '>=', date("Y-m-d", strtotime("-7 day")));
            })
                ->select('dramas.id as drama_id', 'dramas.title as drama_title',
                    'episodes.id as episode_id', 'episodes.title as episode_title', 'episodes.reviews as reviews',
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
                ->get();
        }
        else
        {
            $episodes = Episode::join('dramas', function($join) use($type)
            {
                $join->on('episodes.drama_id', '=', 'dramas.id')
                    ->where('dramas.type', '=', $type)
                    ->where('episodes.release_date', '>=', date("Y-m-d", strtotime("-7 day")));
            })
                ->select('dramas.id as drama_id', 'dramas.title as drama_title',
                    'episodes.id as episode_id', 'episodes.title as episode_title', 'episodes.reviews as reviews',
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
                ->get();
        }

        $episodes = $episodes->sortByDesc('release_date');
        $today = date("Y-m-d", strtotime("now"));
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $thedaybefore = date("Y-m-d", strtotime("-2 day"));
        $todays = [];
        $yesterdays = [];
        $thedaybefores = [];
        $thisweeks = [];
        foreach($episodes as $episode)
        {
            if($episode->release_date == $today)
                $todays[] = $episode;
            else if($episode->release_date == $yesterday)
                $yesterdays[] = $episode;
            else if($episode->release_date == $thedaybefore)
                $thedaybefores[] = $episode;
            else
                $thisweeks[] = $episode;
        }

        if($type < 0)
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->take(20)->get();
        }
        else
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('dramas.type', '=', $type)
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->take(20)->get();
        }

        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['episode' => function($query)
        {
            $query->select('id', 'title');
        }]);

        $dramas = Drama::select('id', 'title', 'sc')->orderBy('id', 'desc')->take(20)->get();
        $histories = History::where('model', 0)->where('type', 0)->orderBy('model_id', 'desc')->take(20)->get();
        $histories->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        return view('index')->with('type', $type)->with('todays', $todays)->with('yesterdays', $yesterdays)
            ->with('thisweeks', $thisweeks)->with('thedaybefores', $thedaybefores)
            ->withReviews($reviews)->withDramas($dramas)->withHistories($histories);
	}

}
