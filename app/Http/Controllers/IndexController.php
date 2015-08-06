<?php namespace App\Http\Controllers;

use App\Drama;
use App\Episode;
use App\History;
use App\Review;
use App\Favorite;

use Illuminate\Http\Request;
use DB;

class IndexController extends Controller {

	public function index(Request $request)
	{
        //判断url中是否有性向参数，若没有就默认性向为耽美type=0
        if($request->has('type'))
            $type = $request->input('type');
        else
            $type = 0;

        //若type为-1，则查询全部新剧，否则按性向即type值查询
        if($type < 0)
        {
            $episodes = Episode::join('dramas', function($join)
            {
                $join->on('episodes.drama_id', '=', 'dramas.id')
                    ->where('episodes.release_date', '>=', date("Y-m-d", strtotime("-7 day")));
            })
                ->select('dramas.id as drama_id', 'dramas.title as drama_title', 'dramas.type as type',
                    'episodes.id as episode_id', 'episodes.title as episode_title', 'episodes.reviews as reviews',
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias', 'episodes.poster_url as poster_url',
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
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias', 'episodes.poster_url as poster_url',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
                ->get();
        }
        //按添加顺序倒序排列
        $episodes = $episodes->sortByDesc('id');
        $top10 = $episodes->take(10);
        //将一周新剧按发剧日期分组
        $episodes = $episodes->groupBy('release_date');

        //按照分类选取最新20条评论
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
        //加载评论的用户名和分集标题
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['episode' => function($query)
        {
            $query->select('id', 'title');
        }]);

        //查询30天评论数前10的剧集id
        if($type < 0)
        {
            $hotDramas = Review::join('dramas', function($join) use($type)
            {
                $join->on('reviews.drama_id', '=', 'dramas.id')
                    ->where('reviews.created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")));
            })
                ->select(DB::raw('count(*) as review_count, drama_id, dramas.title as title'))
                ->groupBy('drama_id')
                ->orderBy('review_count', 'desc')
                ->take(10)
                ->get();
        }
        else
        {
            $hotDramas = Review::join('dramas', function($join) use($type)
            {
                $join->on('reviews.drama_id', '=', 'dramas.id')
                    ->where('dramas.type', '=', $type)
                    ->where('reviews.created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")));
            })
                ->select(DB::raw('count(*) as review_count, drama_id, dramas.title as title'))
                ->groupBy('drama_id')
                ->orderBy('review_count', 'desc')
                ->take(10)
                ->get();
        }

        //查询30天收藏前10的剧集id
        if($type < 0)
        {
            $hotFavorites = Favorite::join('dramas', function($join) use($type)
            {
                $join->on('favorites.drama_id', '=', 'dramas.id')
                    ->where('favorites.created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")));
            })
                ->select(DB::raw('count(*) as favorite_count, drama_id, title'))
                ->groupBy('drama_id')
                ->orderBy('favorite_count', 'desc')
                ->take(10)
                ->get();
        }
        else
        {
            $hotFavorites = Favorite::join('dramas', function($join) use($type)
            {
                $join->on('favorites.drama_id', '=', 'dramas.id')
                    ->where('dramas.type', '=', $type)
                    ->where('favorites.created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")));
            })
                ->select(DB::raw('count(*) as favorite_count, drama_id, title'))
                ->groupBy('drama_id')
                ->orderBy('favorite_count', 'desc')
                ->take(10)
                ->get();
        }

        //查询最新15条新剧的添加历史
        $dramas = Drama::select('title', 'sc')->orderBy('id', 'desc')->take(15)->get();
        $histories = History::select('user_id', 'model_id', 'created_at')->where('model', 0)->where('type', 0)
            ->orderBy('model_id', 'desc')->take(15)->get();
        $histories->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);

        return view('index', ['type' => $type, 'episodes' => $episodes, 'top10' => $top10, 'reviews' => $reviews,
            'hotDramas' => $hotDramas, 'hotFavorites' => $hotFavorites, 'dramas' => $dramas, 'histories' => $histories]);
	}

}
