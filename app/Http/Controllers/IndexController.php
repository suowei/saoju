<?php namespace App\Http\Controllers;

use App\Drama;
use App\Dramalist;
use App\Dramaver;
use App\Episode;
use App\Ftep;
use App\Ftfav;
use App\Ftrev;
use App\History;
use App\Listfav;
use App\Live;
use App\Livefav;
use App\Liverev;
use App\Review;
use App\Favorite;

use App\Song;
use App\Songfav;
use App\Songrev;
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
                ->select('dramas.title as drama_title', 'dramas.type as type', 'dramas.original as original', 'dramas.author as author',
                    'episodes.id as episode_id', 'episodes.title as episode_title', 'episodes.reviews as reviews',
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias', 'episodes.poster_url as poster_url',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
                ->orderBy('episode_id', 'desc')
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
                ->select('dramas.title as drama_title', 'dramas.type as type', 'dramas.original as original', 'dramas.author as author',
                    'episodes.id as episode_id', 'episodes.title as episode_title', 'episodes.reviews as reviews',
                    'episodes.release_date as release_date', 'dramas.sc as sc', 'episodes.alias as alias', 'episodes.poster_url as poster_url',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.state as state', 'episodes.duration as duration')
                ->orderBy('episode_id', 'desc')
                ->get();
        }
        //将一周新剧按发剧日期分组
        $episodes = $episodes->groupBy('release_date');

        //查询60天收藏数前10的剧单
        $lists = Listfav::with(['dramalist' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select(DB::raw('count(*) as favorite_count, list_id'))
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-60 day")))
            ->groupBy('list_id')->orderBy('favorite_count', 'desc')->take(10)->get();

        //最新剧单
        $newlists = Dramalist::select('id', 'title')->orderBy('id', 'desc')->take(10)->get();

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

        //查询最新10条新剧的添加历史
        $versions = Dramaver::select('user_id', 'drama_id', 'created_at')->where('first', 1)
            ->orderBy('drama_id', 'desc')->take(10)->get();
        $versions->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $versions->load(['drama' => function($query)
        {
            $query->select('id', 'title', 'sc');
        }]);
        $versions = $versions->filter(function ($version) {
            return isset($version->drama->id);
        });

        return view('index', ['type' => $type, 'episodes' => $episodes,
            'lists' => $lists, 'newlists' => $newlists,
            'hotDramas' => $hotDramas, 'hotFavorites' => $hotFavorites, 'versions' => $versions]);
	}

    public function reviews(Request $request)
    {
        if($request->has('type'))
            $type = $request->input('type');
        else
            $type = 0;
        if($type < 0)
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('reviews.visible', '=', 1)
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->simplePaginate(20);
        }
        else
        {
            $reviews = Review::join('dramas', 'reviews.drama_id', '=', 'dramas.id')
                ->where('dramas.type', '=', $type)
                ->where('reviews.visible', '=', 1)
                ->select('reviews.*', 'dramas.title as drama_title')
                ->orderBy('id', 'desc')->simplePaginate(20);
        }
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['episode' => function($query)
        {
            $query->select('id', 'title');
        }]);
        return view('review.reviews', ['reviews' => $reviews]);
    }

    public function zhoubian()
    {
        $newsongs = Song::select('id', 'title', 'alias', 'artist')->orderBy('id', 'desc')->take(15)->get();
        $songrevs = Songrev::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'song' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->orderBy('id', 'desc')->take(10)->get();
        $hotrevsongs = Songrev::with(['song' => function($query)
        {
            $query->select('id', 'title', 'artist');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as review_count, song_id'))
            ->groupBy('song_id')
            ->orderBy('review_count', 'desc')
            ->take(15)
            ->get();
        $hotfavsongs = Songfav::with(['song' => function($query)
        {
            $query->select('id', 'title', 'artist');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as favorite_count, song_id'))
            ->groupBy('song_id')
            ->orderBy('favorite_count', 'desc')
            ->take(15)
            ->get();

        $newcreatedfteps = Ftep::with(['ft' => function($query)
        {
            $query->select('id', 'title', 'host');
        }])
            ->select('id', 'ft_id', 'title', 'release_date')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        $ftrevs = Ftrev::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'ft' => function($query)
        {
            $query->select('id', 'title');
        }, 'ftep' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        $hotrevfts = Ftrev::with(['ft' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as review_count, ft_id'))
            ->groupBy('ft_id')
            ->orderBy('review_count', 'desc')
            ->take(10)
            ->get();
        $hotfavfts = Ftfav::with(['ft' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as favorite_count, ft_id'))
            ->groupBy('ft_id')
            ->orderBy('favorite_count', 'desc')
            ->take(10)
            ->get();

        $todaylives = Live::select('id', 'title', 'showtime')
            ->whereRaw('date(showtime) = curdate()')->orderBy('showtime')->get();
        $newlives = Live::select('id', 'title', 'showtime')->orderBy('id', 'desc')->take(15)->get();
        $liverevs = Liverev::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->orderBy('id', 'desc')->take(10)->get();
        $hotrevlives = Liverev::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as review_count, live_id'))
            ->groupBy('live_id')
            ->orderBy('review_count', 'desc')
            ->take(15)
            ->get();
        $hotfavlives = Livefav::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime("-30 day")))
            ->select(DB::raw('count(*) as favorite_count, live_id'))
            ->groupBy('live_id')
            ->orderBy('favorite_count', 'desc')
            ->take(15)
            ->get();
        return view('zhoubian', ['newsongs' => $newsongs, 'songrevs' => $songrevs, 'hotrevsongs' => $hotrevsongs,
            'hotfavsongs' => $hotfavsongs, 'newcreatedfteps' => $newcreatedfteps,
            'ftrevs' => $ftrevs, 'hotrevfts' => $hotrevfts, 'hotfavfts' => $hotfavfts, 'todaylives' => $todaylives,
            'newlives' => $newlives, 'liverevs' => $liverevs, 'hotrevlives' => $hotrevlives, 'hotfavlives' => $hotfavlives]);
    }

    public function guide()
    {
        return view('guide');
    }
}
