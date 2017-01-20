<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Epfav;
use App\Episode;
use App\Favorite;
use App\Live;
use App\Review;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function weixin()
    {
        $newEpisodes = Episode::join('dramas', function($join)
        {
            $join->on('episodes.drama_id', '=', 'dramas.id');
        })
            ->select('dramas.title as drama_title', 'dramas.type as type', 'dramas.original as original',
                'dramas.author as author', 'dramas.era as era', 'dramas.genre as genre', 'dramas.sc as cv', 'episodes.id as id',
                'episodes.title as episode_title', 'episodes.alias as alias', 'episodes.sc as sc', 'episodes.url as url')
            ->where('release_date', date("Y-m-d", strtotime("-1 day")))
            ->orderBy('type')
            ->get();
        $newEpisodesCount = count($newEpisodes);
        $newEpisodes = $newEpisodes->groupBy('type');
        $todayLivesCount = Live::select('id')
            ->whereRaw('date(showtime) = curdate()')
            ->count();
        return view('admin.weixin', ['newEpisodes' => $newEpisodes, 'newEpisodesCount' => $newEpisodesCount,
            'todayLivesCount' => $todayLivesCount]);
    }

    public function recommend()
    {
        $favorites = Favorite::with(['drama' => function($query){
            $query->select('id', 'title', 'sc');
        }])
            ->select(DB::raw('drama_id, count(*) as count, avg(rating) as average'))
            ->where('rating', '<>', 0.0)
            ->groupBy('drama_id')
            ->having('count', '>=', 3)
            ->having('average', '>=', 3.0)
            ->orderBy('drama_id', 'desc')
            ->take(10)
            ->get();
        $epfavs = Epfav::with('episode.drama')
            ->select(DB::raw('episode_id, count(*) as count, avg(rating) as average'))
            ->where('rating', '<>', 0.0)
            ->groupBy('episode_id')
            ->having('count', '>=', 2)
            ->having('average', '>=', 3.0)
            ->orderBy('episode_id', 'desc')
            ->take(10)
            ->get();
        return view('admin.recommend', ['favorites' => $favorites, 'epfavs' => $epfavs]);
    }

    public function dramaRating(Request $request)
    {
        $drama = Drama::find($request->input('id'), ['title', 'sc']);
        $favorites = Favorite::select('type', 'rating')->where('drama_id', $request->input('id'))->get();
        for($rating = 1; $rating <= 10; $rating++)
            $ratings[$rating] = 0;
        $count = 0;
        $sum = 0.0;
        foreach($favorites as $favorite) {
            if($favorite->rating != 0) {
                $ratings[$favorite->rating * 2]++;
                $count++;
                $sum += $favorite->rating;
            }
        }
        $average = $count > 0 ? $sum / $count : 0.0;
        return view('admin.dramarating', ['drama' => $drama, 'favorites' => $favorites,
            'ratings' => $ratings, 'count' => $count, 'average' => $average]);
    }

    public function episodeRating(Request $request)
    {
        $episode = Episode::find($request->input('id'), ['drama_id', 'title']);
        $episode->drama = Drama::find($episode->drama_id, ['title', 'sc']);
        $favorites = Epfav::select('type', 'rating')->where('episode_id', $request->input('id'))->get();
        for($rating = 1; $rating <= 10; $rating++)
            $ratings[$rating] = 0;
        $count = 0;
        $sum = 0.0;
        foreach($favorites as $favorite) {
            if($favorite->rating != 0) {
                $ratings[$favorite->rating * 2]++;
                $count++;
                $sum += $favorite->rating;
            }
        }
        $average = $count > 0 ? $sum / $count : 0.0;
        return view('admin.episoderating', ['episode' => $episode, 'favorites' => $favorites,
            'ratings' => $ratings, 'count' => $count, 'average' => $average]);
    }

    public function banReview()
    {
        return view('admin.banreview');
    }

    public function deleteReview(Request $request)
    {
        $this->validate($request, [
            'review_id' => 'required|integer',
            'created_at' => 'required|date',
            'reason' => 'required|max:255',
        ]);

        $review = Review::find($request->input('review_id'));
        if ($review->created_at == $request->input('created_at'))
        {
            $review->banned = $request->input('reason');
            if($review->save())
            {
                return redirect('/');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('屏蔽失败');
            }
        }
        return redirect()->back()->withInput()->withErrors('校验失败，请仔细核对评论ID与评论时间');
    }

}
