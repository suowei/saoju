<?php namespace App\Http\Controllers;

use App\Drama;
use App\Epfav;
use App\History;
use App\Favorite;
use App\Review;
use App\Episode;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
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

        $history = new History;
        $history->user_id = $request->user()->id;
        $history->model = 0;
        $history->type = 0;

        if($drama = Drama::create(Input::all()))
        {
            $history->model_id = $drama->id;
            $history->content = '剧名：'.$drama->title;
            if($drama->alias != '')
                $history->content .= '；副标题/别名：'.$drama->alias;
            $history->content .= '；性向：';
            switch($drama->type)
            {
                case 0:
                    $history->content .= '耽美';
                    break;
                case 1:
                    $history->content .= '全年龄';
                    break;
                case 2:
                    $history->content .= '言情';
                    break;
                case 3:
                    $history->content .= '百合';
                    break;
            }
            $history->content .= '；时代：';
            switch($drama->era)
            {
                case 0:
                    $history->content .= '现代';
                    break;
                case 1:
                    $history->content .= '古风';
                    break;
                case 2:
                    $history->content .= '民国';
                    break;
                case 3:
                    $history->content .= '未来';
                    break;
                case 4:
                    $history->content .= '其他';
                    break;
            }
            if($drama->genre != '')
                $history->content .= '；其他描述：'.$drama->genre;
            $history->content .= '；原创性：' . ($drama->original == 1 ? '原创' : '改编');
            $history->content .= '；期数：'.$drama->count;
            $history->content .= '；进度：';
            switch($drama->state)
            {
                case 0:
                    $history->content .= '连载';
                    break;
                case 1:
                    $history->content .= '完结';
                    break;
                case 2:
                    $history->content .= '已坑';
                    break;
            }
            $history->content .= '；主役CV：'.$drama->sc;
            if($drama->poster_url != '')
                $history->content .= '；海报地址：'.$drama->poster_url;
            if($drama->introduction != '')
                $history->content .= '；剧情简介：'.$drama->introduction;
            $history->save();

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
        }])->select('sc_id', 'job', 'note')->distinct()->where('drama_id', $id)->whereIn('job', [0, 1, 2, 3, 4, 11])->orderBy('job')->get();
        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'episode' => function($query) {
            $query->select('id', 'title');
        }])->select('id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->where('drama_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Favorite::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'updated_at')->where('drama_id', $id)->orderBy('updated_at', 'desc')->take(10)->get();
        //若用户已登录则取得其对整部剧及每个分集的收藏状态，及其全部评论
        $epfavs = [];
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Favorite::select('id', 'type', 'rating')->where('user_id', $user_id)->where('drama_id', $id)->first();
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
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('drama.show', ['drama' => $drama, 'episodes' => $episodes, 'reviews' => $reviews, 'roles' => $roles,
            'favorites' => $favorites, 'favorite' => $favorite, 'epfavs' => $epfavs, 'userReviews' => $userReviews]);
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

        $history = new History;
        $history->user_id = $request->user()->id;
        $history->model = 0;
        $history->type = 1;
        $history->model_id = $id;
        $history->content = '';
        if($drama->title != $request->input('title'))
            $history->content .= '剧名：'.$request->input('title').'；';
        if($drama->alias != $request->input('alias'))
            $history->content .= '副标题/别名：'.$request->input('alias').'；';
        if($drama->type != $request->input('type'))
        {
            $history->content .= '性向：';
            switch($request->input('type'))
            {
                case 0:
                    $history->content .= '耽美；';
                    break;
                case 1:
                    $history->content .= '全年龄；';
                    break;
                case 2:
                    $history->content .= '言情；';
                    break;
                case 3:
                    $history->content .= '百合；';
                    break;
            }
        }
        if($drama->era != $request->input('era'))
        {
            $history->content .= '时代：';
            switch($request->input('era'))
            {
                case 0:
                    $history->content .= '现代；';
                    break;
                case 1:
                    $history->content .= '古风；';
                    break;
                case 2:
                    $history->content .= '民国；';
                    break;
                case 3:
                    $history->content .= '未来；';
                    break;
                case 4:
                    $history->content .= '其他；';
                    break;
            }
        }
        if($drama->genre != $request->input('genre'))
            $history->content .= '其他描述：'.$request->input('genre').'；';
        if($drama->original != $request->input('original'))
            $history->content .= '原创性：' . ($request->input('original') == 1 ? '原创；' : '改编；');
        if($drama->count != $request->input('count'))
            $history->content .= '期数：'.$request->input('count').'；';
        if($drama->state != $request->input('state'))
        {
            $history->content .= '进度：';
            switch($request->input('state'))
            {
                case 0:
                    $history->content .= '连载；';
                    break;
                case 1:
                    $history->content .= '完结；';
                    break;
                case 2:
                    $history->content .= '已坑；';
                    break;
            }
        }
        if($drama->sc != $request->input('sc'))
            $history->content .= '主役CV：'.$request->input('sc').'；';
        if($drama->poster_url != $request->input('poster_url'))
            $history->content .= '海报地址：'.$request->input('poster_url').'；';
        if($drama->introduction != $request->input('introduction'))
            $history->content .= '剧情简介：'.$request->input('introduction').'；';
        $history->content = mb_substr($history->content, 0 , -1);

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
            if(!empty($history->content))
                $history->save();
            return redirect()->route('drama.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
	}

	public function destroy(Request $request, $id)
	{
        $history = History::select('id', 'user_id')->where('model', 0)->where('model_id', $id)->where('type', 0)->first();
        if($history->user_id != $request->user()->id)
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
            $history->delete();
            //删除剩余编辑历史
            $histories = History::select('id')->where('model', 0)->where('model_id', $id)->get();
            foreach($histories as $history)
            {
                $history->delete();
            }
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

}
