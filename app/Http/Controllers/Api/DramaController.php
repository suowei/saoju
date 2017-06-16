<?php namespace App\Http\Controllers\Api;

use App\Drama;
use App\Dramalist;
use App\Dramaver;
use App\Ed;
use App\Epfav;
use App\History;
use App\Favorite;
use App\Item;
use App\Review;
use App\Episode;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use App\Song;
use App\Tag;
use App\Tagmap;
use Illuminate\Http\Request;

use Input, Auth, DB;

class DramaController extends Controller {

    public function __construct()
    {
        $this->middleware('apiauth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        //数据库查询参数
        $scope = [];
        //性向筛选
        if($request->has('type'))
        {
            $scope['type'] = ['=', $request->input('type')];
        }
        //时代筛选
        if($request->has('era'))
        {
            $scope['era'] = ['=', $request->input('era')];
        }
        //原创性筛选
        if($request->has('original'))
        {
            $scope['original'] = ['=', $request->input('original')];
        }
        //作者筛选
        if($request->has('author'))
        {
            $scope['author'] = ['=', $request->input('author')];
        }
        //进度筛选，结合state与count字段
        if($request->has('state'))
        {
            switch($request->input('state'))
            {
                case 0://连载中
                    $scope['state'] = ['=', 0];
                    break;
                case 1://已完结
                    $scope['state'] = ['=', 1];
                    $scope['count'] = ['>', 1];
                    break;
                case 2://全一期
                    $scope['state'] = ['=', 1];
                    $scope['count'] = ['=', 1];
                    break;
                case 3://已坑
                    $scope['state'] = ['=', 2];
                    break;
            }
        }
        //主役筛选
        if($request->has('cv'))
        {
            $scope['sc'] = ['LIKE', '%'.$request->input('cv').'%'];
        }
        //排序
        if($request->has('sort'))
        {
            $sort = $request->input('sort');
        }
        else
        {
            $sort = 'id';
        }
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $dramas = Drama::select('id', 'title', 'alias', 'type', 'era', 'genre', 'original', 'count', 'state', 'sc')
            ->multiwhere($scope)
            ->orderBy($sort, $order)
            ->simplePaginate(20);
        return $dramas;
    }

    public function show(Request $request, $id)
    {
        $drama = Drama::find($id, ['id', 'title', 'alias', 'type', 'era', 'genre',
            'original', 'author', 'count', 'state', 'sc', 'introduction', 'reviews']);
        $drama->load(['episodes' => function($query)
        {
            $query->select('id', 'drama_id', 'title', 'alias', 'release_date', 'poster_url')
                ->orderByRaw('release_date, id')
                ->get();
        }]);
        $drama->commtags = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('drama_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $drama->userFavorite = Favorite::select('id', 'type', 'rating', 'tags')
                ->where('user_id', $user_id)->where('drama_id', $id)->first();
            $drama->userTags = Tagmap::with('tag')
                ->select(DB::raw('count(*) as count, tag_id'))
                ->where('user_id', $user_id)
                ->groupBy('tag_id')
                ->orderBy('count', 'desc')
                ->take(10)->get();
        }
        return $drama;
    }

    public function reviews($id)
    {
        $reviews = Review::with(['user' => function($query)
        {
            $query->select('id', 'name');
        },
            'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'episode_id', 'user_id', 'title', 'content', 'visible', 'created_at', 'banned')
            ->where('drama_id', $id)
            ->where('visible', '<=', 1)
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        return $reviews;
    }
}
