<?php namespace App\Http\Controllers;

use App\Drama;
use App\Dramalist;
use App\Dramaver;
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Input;

class DramaController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
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
        //传递给视图的url参数
        $params = $request->except('page');
        //排序
        if($request->has('sort'))
        {
            $params['sort'] = $request->input('sort');
        }
        else
        {
            $params['sort'] = 'id';
        }
        if($request->has('order'))
        {
            $params['order'] = $request->input('order');
        }
        else
        {
            $params['order'] = 'desc';
        }
        $dramas = Drama::select('id', 'title', 'alias', 'type', 'era', 'genre',
            'original', 'count', 'state', 'sc', 'poster_url', 'introduction')
            ->multiwhere($scope)->orderBy($params['sort'], $params['order'])->paginate(20);
		return view('drama.index', ['params' => $params, 'dramas' => $dramas]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('drama.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'title' => 'required|max:255',
            'alias' => 'max:255',
            'type' =>'required|in:0,1,2,3',
            'era' =>'required|in:0,1,2,3,4',
            'genre' => 'max:255',
            'original' => 'required|in:0,1',
            'count' => 'required|integer',
            'state' => 'required|in:0,1,2',
            'sc' => 'required|max:255',
            'poster_url' => 'url',
        ]);

        if($drama = Drama::create(Input::all()))
        {
            Dramaver::create(['drama_id' => $drama->id, 'user_id' => $request->user()->id, 'first' => 1,
                'title' => $drama->title, 'alias' => $drama->alias, 'type' => $drama->type, 'era' => $drama->era,
                'genre' => $drama->genre, 'original' => $drama->original, 'count' => $drama->count,
                'state' => $drama->state, 'sc' => $drama->sc, 'poster_url' => $drama->poster_url,
                'introduction' => $drama->introduction]);
            return redirect()->route('drama.show', [$drama]);
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
	public function show(Request $request, $id)
	{
        $drama = Drama::find($id, ['id', 'title', 'alias', 'type', 'era', 'genre',
            'original', 'count', 'state', 'sc', 'poster_url', 'introduction', 'reviews', 'favorites']);
        $episodes = Episode::select('id', 'title', 'alias', 'release_date', 'url', 'sc', 'duration', 'poster_url', 'introduction')
            ->where('drama_id', $id)->orderByRaw('release_date, id')->get();
        $roles = Role::with(['sc' => function($query) {
            $query->select('id', 'name');
        }])->select('sc_id', 'job')->distinct()->where('drama_id', $id)->whereIn('job', [0, 1, 2, 3, 4])->get();
        $roles = $roles->groupBy('job');
        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'episode' => function($query) {
            $query->select('id', 'title');
        }])->select('id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->where('drama_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $songs = Song::select('id', 'title', 'artist')->where('drama_id', $id)->get();
        $listids = Item::select('list_id')->where('drama_id', $id)->where('episode_id', 0)
            ->orderBy('id', 'desc')->take(10)->lists('list_id');
        $lists = Dramalist::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'title', 'user_id')->whereIn('id', $listids)->get();
        $favorites = Favorite::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'updated_at')->where('drama_id', $id)->orderBy('updated_at', 'desc')->take(5)->get();
        $tagmaps = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('drama_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->take(20)->get();
        //若用户已登录则取得其对整部剧及每个分集的收藏状态，及其全部评论
        $epfavs = [];
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Favorite::select('id', 'user_id', 'type', 'rating', 'tags')
                ->where('user_id', $user_id)->where('drama_id', $id)->first();
            $ids = $episodes->pluck('id');
            $rows = Epfav::select('episode_id', 'type', 'rating')
                ->where('user_id', $user_id)->whereIn('episode_id', $ids->all())->get();
            foreach($rows as $row)
            {
                $epfavs[$row->episode_id] = $row;
            }
            $userReviews = Review::with(['episode' => function($query) {
                $query->select('id', 'title');
            }])->select('id', 'episode_id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('drama_id', $id)->get();
            $usertags = Tagmap::with('tag')
                ->select(DB::raw('count(*) as count, tag_id'))
                ->where('user_id', $user_id)
                ->groupBy('tag_id')
                ->orderBy('count', 'desc')
                ->take(15)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
            $usertags = [];
        }
        return view('drama.show', ['drama' => $drama, 'episodes' => $episodes, 'reviews' => $reviews, 'songs' => $songs,
            'roles' => $roles, 'lists' => $lists, 'favorites' => $favorites, 'tagmaps' => $tagmaps,
            'favorite' => $favorite, 'epfavs' => $epfavs, 'userReviews' => $userReviews, 'usertags' => $usertags]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $drama = Drama::find($id, ['id', 'title', 'alias', 'type', 'era', 'genre',
            'original', 'count', 'state', 'sc', 'poster_url', 'introduction']);
		return view('drama.edit', ['drama' => $drama]);
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
            'title' => 'required|max:255',
            'alias' => 'max:255',
            'type' =>'required|in:0,1,2,3',
            'era' =>'required|in:0,1,2,3,4',
            'genre' => 'max:255',
            'original' => 'required|in:0,1',
            'count' => 'required|integer',
            'state' => 'required|in:0,1,2',
            'sc' => 'required|max:255',
            'poster_url' => 'url',
        ]);

        $drama = Drama::find($id);
        $drama->title = $request->input('title');
        $drama->alias = $request->input('alias');
        $drama->type = $request->input('type');
        $drama->era = $request->input('era');
        $drama->genre = $request->input('genre');
        $drama->original = $request->input('original');
        $drama->count = $request->input('count');
        $drama->state = $request->input('state');
        $drama->sc = $request->input('sc');
        $drama->poster_url = $request->input('poster_url');
        $drama->introduction = $request->input('introduction');

        if($drama->save())
        {
            $user_id = $request->user()->id;
            $version = Dramaver::where('drama_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Dramaver;
                $version->drama_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->title = $drama->title;
            $version->alias = $drama->alias;
            $version->type = $drama->type;
            $version->era = $drama->era;
            $version->genre = $drama->genre;
            $version->original = $drama->original;
            $version->count = $drama->count;
            $version->state = $drama->state;
            $version->sc = $drama->sc;
            $version->poster_url = $drama->poster_url;
            $version->introduction = $drama->introduction;
            $version->save();

            return redirect()->route('drama.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
	}

	public function destroy(Request $request, $id)
	{
        $version = Dramaver::select('user_id')->where('drama_id', $id)->where('first', 1)->first();
        if($version->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除剧集> <';
        }
        $episode = Episode::select('id')->where('drama_id', $id)->first();
        if($episode)
        {
            return '抱歉，请先逐一删除分集后再删除本剧';
        }
        $favorite = Favorite::select('user_id')->where('drama_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏本剧，不能删除> <';
        }
        $review = Review::select('id')->where('drama_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论本剧，不能删除> <';
        }
        $drama = Drama::find($id, ['id']);
        if($drama->delete())
        {
            return redirect('/');
        }
        else
        {
            return '删除失败';
        }
	}

    public function reviews($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'episode' => function($query) {
            $query->select('id', 'title');
        }])->select('id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->where('drama_id', $id)->paginate(20);
        return view('drama.reviews', ['drama' => $drama, 'reviews' => $reviews]);
    }

    public function histories($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $histories = History::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'content', 'created_at')->where('model', 0)->where('model_id', $id)->get();
        return view('drama.histories', ['drama' => $drama, 'histories' => $histories]);
    }

    public function search(Request $request)
    {
        if($request->input('title') != '')
        {
            $dramas = Drama::select('id', 'sc')->where('title', $request->input('title'))->get();
            return $dramas;
        }
    }

    public function favorites($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $favorites = Favorite::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'updated_at')->where('drama_id', $id)->orderBy('updated_at')->paginate(20);
        return view('drama.favorites')->withDrama($drama)->withFavorites($favorites);
    }

    public function sc($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $episodes = Episode::select('id', 'title')->where('drama_id', $id)->orderByRaw('release_date, id')->get();
        $roles = Role::with(['sc' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'episode_id', 'sc_id', 'job', 'note')->where('drama_id', $id)->orderBy('job')->get();
        $roles = $roles->groupBy('episode_id');
        return view('drama.sc', ['drama' => $drama, 'episodes' => $episodes, 'roles' => $roles]);
    }

    public function versions($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $versions = Dramaver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'title', 'alias', 'type', 'era', 'genre', 'original',
                'count', 'state', 'sc', 'poster_url', 'introduction', 'created_at', 'updated_at')
            ->where('drama_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('drama.versions', ['drama' => $drama, 'versions' => $versions]);
    }

    public function lists($id)
    {
        $drama = Drama::find($id, ['id', 'title']);
        $items = Item::select('list_id', 'created_at')->where('drama_id', $id)->where('episode_id', 0)
            ->orderBy('id', 'desc')->paginate(50);
        $listids = $items->pluck('list_id');
        $lists = Dramalist::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'title', 'user_id')->whereIn('id', $listids)->get();
        return view('drama.lists', ['drama' => $drama, 'items' => $items, 'lists' => $lists]);
    }

    public function tags($id)
    {
        $tagmaps = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('drama_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->take(20)->get();
        $tags = [];
        foreach($tagmaps as $tagmap)
        {
            $tags[] = ['text' => $tagmap->tag->name, 'weight' => $tagmap->count, 'link' => '/drama/tag/'.$tagmap->tag->name];
        }
        return $tags;
    }

    public function tag(Request $request, $tag)
    {
        if($request->has('sort'))
        {
            $sort = $request->input('sort');
        }
        else
        {
            $sort = 'tagcount';
        }
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $tag = Tag::where('name', $tag)->first();
        if(!$tag)
            return view('search.tag', ['message' => '未找到结果']);
        if($sort == 'tagcount')
        {
            $dramas = Tagmap::with(['drama' => function($query) {
                $query->select('id', 'title', 'alias', 'sc', 'type', 'era', 'genre',
                    'original', 'count', 'state', 'poster_url', 'introduction');
            }])
                ->select(DB::raw('count(*) as count, drama_id'))
                ->where('tag_id', $tag->id)
                ->groupBy('drama_id')
                ->orderBy('count', 'desc')
                ->paginate(20);
        }
        else
        {
            $dramas = Drama::whereExists(function($query) use($tag)
            {
                $query->select(DB::raw(1))
                    ->from('tagmaps')
                    ->whereRaw('tagmaps.tag_id = '.$tag->id.' and tagmaps.drama_id = dramas.id');
            })
                ->select('id', 'title', 'alias', 'sc', 'type', 'era', 'genre',
                    'original', 'count', 'state', 'poster_url', 'introduction')
                ->orderBy($sort, 'desc')->paginate(20);
        }
        if(!$dramas || !$dramas->total())
            return view('search.tag', ['message' => '未找到结果']);
        return view('drama.tag', ['tag' => $tag->name, 'dramas' => $dramas, 'sort' => $sort, 'order' => $order]);
    }

}
