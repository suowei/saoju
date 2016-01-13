<?php namespace App\Http\Controllers\App;

use App\Dramalist;
use App\Episode;

use App\Favorite;
use App\Listfav;
use App\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller {

    public function episodes()
    {
        $episodes = Episode::join('dramas', function($join)
        {
            $join->on('episodes.drama_id', '=', 'dramas.id');
        })
            ->select('dramas.title as dramaTitle', 'dramas.type as type', 'dramas.original as original',
                'episodes.id as episodeId', 'episodes.title as episodeTitle', 'episodes.release_date as releaseDate',
                'dramas.sc as sc', 'episodes.alias as alias', 'episodes.poster_url as posterUrl',
                'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
            ->orderByRaw('release_date desc, episodes.id desc')->simplePaginate(20);
        return $episodes;
    }

    public function lists()
    {
        //最新剧单
        $newLists = Dramalist::select('id', 'title')->orderBy('id', 'desc')->take(10)->get();
        //查询60天收藏数前10的剧单
        $hotLists = Listfav::with(['dramalist' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select(DB::raw('count(*) as favorite_count, list_id'))
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-60 day")))
            ->groupBy('list_id')->orderBy('favorite_count', 'desc')->take(10)->get();
        //查询30天评论数前10的剧集id
        $hotDramas = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as review_count, drama_id'))
            ->groupBy('drama_id')
            ->orderBy('review_count', 'desc')
            ->take(10)
            ->get();
        //查询30天收藏前10的剧集id
        $hotFavorites = Favorite::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as favorite_count, drama_id'))
            ->groupBy('drama_id')
            ->orderBy('favorite_count', 'desc')
            ->take(10)
            ->get();
        $data = ['newLists' => $newLists, 'hotLists' => $hotLists, 'hotDramas' => $hotDramas, 'hotFavorites' => $hotFavorites];
        return $data;
    }
}
