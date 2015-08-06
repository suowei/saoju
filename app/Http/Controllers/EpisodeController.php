<?php namespace App\Http\Controllers;

use App\Epfav;
use App\Episode;
use App\Review;
use App\History;
use App\Drama;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use Illuminate\Http\Request;

use Input, Auth;

class EpisodeController extends Controller {

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
            $scope['dramas.type'] = ['=', $request->input('type')];
        }
        //时代筛选
        if($request->has('era'))
        {
            $scope['dramas.era'] = ['=', $request->input('era')];
        }
        //原创性筛选
        if($request->has('original'))
        {
            $scope['dramas.original'] = ['=', $request->input('original')];
        }
        //进度筛选，结合state与count字段
        if($request->has('state'))
        {
            switch($request->input('state'))
            {
                case 0://连载中
                    $scope['dramas.state'] = ['=', 0];
                    break;
                case 1://已完结
                    $scope['dramas.state'] = ['=', 1];
                    $scope['dramas.count'] = ['>', 1];
                    break;
                case 2://全一期
                    $scope['dramas.state'] = ['=', 1];
                    $scope['dramas.count'] = ['=', 1];
                    break;
                case 3://已坑
                    $scope['dramas.state'] = ['=', 2];
                    break;
            }
        }
        //起止日期筛选
        if($request->has('startdate') || $request->has('enddate'))
        {
            if(!$request->has('startdate'))//如果只有截止日期
                $scope['release_date'] = ['<=', $request->input('enddate')];
            else if(!$request->has('enddate'))//如果只有起始日期
                $scope['release_date'] = ['>=', $request->input('startdate')];
            else//既有起始日期又有终止日期时需要使用between另外编写查询语句
                $release_date = [$request->input('startdate'), $request->input('enddate')];
        }
        //主役筛选
        if($request->has('cv'))
        {
            $scope['dramas.sc'] = ['LIKE', '%'.$request->input('cv').'%'];
        }
        //SC筛选
        if($request->has('sc'))
        {
            $scope['episodes.sc'] = ['LIKE', '%'.$request->input('sc').'%'];
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
        //连接剧集表查询
        if(isset($release_date))
        {
            $episodes = Episode::whereBetween('release_date', $release_date)
                ->join('dramas', function($join) use($scope)
            {
                $join->on('episodes.drama_id', '=', 'dramas.id');
                foreach($scope as $key => $value)
                {
                    $join = $join->where($key, $value[0], $value[1]);
                }
            })
                ->select('episodes.*', 'dramas.id as drama_id', 'dramas.title as drama_title', 'dramas.type as type',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.original as original', 'dramas.state as state',
                    'dramas.sc as cv')
                ->orderBy('episodes.'.$params['sort'], $params['order'])->paginate(20);
        }
        else
        {
            $episodes = Episode::join('dramas', function($join) use($scope)
            {
                $join->on('episodes.drama_id', '=', 'dramas.id');
                foreach($scope as $key => $value)
                {
                    $join = $join->where($key, $value[0], $value[1]);
                }
            })
                ->select('episodes.*', 'dramas.id as drama_id', 'dramas.title as drama_title', 'dramas.type as type',
                    'dramas.era as era', 'dramas.genre as genre', 'dramas.original as original', 'dramas.state as state',
                    'dramas.sc as cv')
                ->orderBy('episodes.'.$params['sort'], $params['order'])->paginate(20);
        }
        return view('episode.index', ['params'=> $params, 'episodes' => $episodes]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        $drama = Drama::find($request->input('drama'), ['id', 'title']);
        return view('episode.create', ['drama' => $drama]);
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
            'release_date' => 'required|date',
            'url' => 'url',
            'sc' => 'required',
            'duration' => 'required|integer',
            'poster_url' => 'url',
            'drama_id' => 'required',
        ]);

        $history = new History;
        $history->user_id = $request->user()->id;
        $history->model = 1;
        $history->type = 0;

        if($episode = Episode::create(Input::all()))
        {
            $history->model_id = $episode->id;
            $history->content = '分集标题：'.$episode->title;
            if($episode->alias != '')
                $history->content .= '；副标题：'.$episode->alias;
            $history->content .= '；发布日期：'.$episode->release_date;
            if($episode->url != '')
                $history->content .= '；发布地址：'.$episode->url;
            $history->content .= '；SC表：'.$episode->sc;
            $history->content .= '；时长：'.$episode->duration;
            if($episode->poster_url != '')
                $history->content .= '；海报地址：'.$episode->poster_url;
            if($episode->introduction != '')
                $history->content .= '；本集简介：'.$episode->introduction;
            $history->save();

            return redirect()->route('episode.show', [$episode]);
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
        $episode = Episode::find($id, ['id', 'drama_id', 'title', 'alias', 'release_date', 'url', 'sc',
            'duration', 'poster_url', 'introduction', 'reviews', 'favorites']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('episode_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Epfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'updated_at')
            ->where('episode_id', $id)->orderBy('updated_at', 'desc')->take(5)->get();
        $roles = Role::with(['sc' => function($query) {
            $query->select('id', 'name');
        }])->select('sc_id', 'job', 'note')->where('episode_id', $id)->orderBy('job')->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Epfav::select('type', 'rating')->where('user_id', $user_id)->where('episode_id', $id)->first();
            $userReviews = Review::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('episode_id', $id)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('episode.show', ['episode' => $episode, 'drama' => $drama, 'reviews' => $reviews,
            'favorites' => $favorites, 'roles' => $roles, 'favorite' => $favorite, 'userReviews' => $userReviews]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $episode = Episode::find($id, ['id', 'drama_id', 'title', 'alias', 'release_date', 'url',
            'sc', 'duration', 'poster_url', 'introduction']);
        $drama = Drama::find($episode->drama_id, ['title']);
        return view('episode.edit', ['episode' => $episode, 'drama' => $drama]);
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
            'release_date' => 'required|date',
            'url' => 'url',
            'sc' => 'required',
            'duration' => 'required|integer',
            'poster_url' => 'url',
        ]);

        $episode = Episode::find($id);

        $history = new History;
        $history->user_id = $request->user()->id;
        $history->model = 1;
        $history->type = 1;
        $history->model_id = $id;
        $history->content = '';
        if($episode->title != $request->input('title'))
            $history->content .= '分集标题：'.$request->input('title').'；';
        if($episode->alias != $request->input('alias'))
            $history->content .= '副标题：'.$request->input('alias').'；';
        if($episode->release_date != $request->input('release_date'))
            $history->content .= '发布日期：'.$request->input('release_date').'；';
        if($episode->url != $request->input('url'))
            $history->content .= '发布地址：'.$request->input('url').'；';
        if($episode->sc != $request->input('sc'))
            $history->content .= 'SC表：'.$request->input('sc').'；';
        if($episode->duration != $request->input('duration'))
            $history->content .= '时长：'.$request->input('duration').'；';
        if($episode->poster_url != $request->input('poster_url'))
            $history->content .= '海报地址：'.$request->input('poster_url').'；';
        if($episode->introduction != $request->input('introduction'))
            $history->content .= '本集简介：'.$request->input('introduction').'；';
        $history->content = mb_substr($history->content, 0 , -1);

        $episode->title = $request->input('title');
        $episode->alias = $request->input('alias');
        $episode->release_date = $request->input('release_date');
        $episode->url = $request->input('url');
        $episode->sc = $request->input('sc');
        $episode->duration = $request->input('duration');
        $episode->poster_url = $request->input('poster_url');
        $episode->introduction = $request->input('introduction');

        if($episode->save())
        {
            if(!empty($history->content))
                $history->save();
            return redirect()->route('episode.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
	}

	public function destroy(Request $request, $id)
	{
		$history = History::select('id', 'user_id')->where('model', 1)->where('model_id', $id)->where('type', 0)->first();
        if($history->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除分集> <';
        }
        $favorite = Epfav::select('user_id')->where('episode_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏本集，不能删除> <';
        }
        $review = Review::select('id')->where('episode_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论本集，不能删除> <';
        }
        $role = Role::select('id')->where('episode_id', $id)->first();
        if($role)
        {
            return '抱歉，请先逐一删除SC关联后再删除本集';
        }
        $episode = Episode::find($id, ['id', 'drama_id']);
        if($episode->delete())
        {
            $history->delete();
            //删除剩余编辑历史
            $histories = History::select('id')->where('model', 1)->where('model_id', $id)->get();
            foreach($histories as $history)
            {
                $history->delete();
            }
            return redirect()->route('drama.show', [$episode->drama_id]);
        }
        else
        {
            return '删除失败';
        }
	}

    public function reviews($id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $reviews = Review::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')->where('episode_id', $id)->paginate(20);
        return view('episode.reviews', ['episode' => $episode, 'drama' => $drama, 'reviews' => $reviews]);
    }

    public function histories($id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $histories = History::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'content', 'created_at')->where('model', 1)->where('model_id', $id)->get();
        return view('episode.histories', ['episode' => $episode, 'drama' => $drama, 'histories' => $histories]);
    }

    public function favorites($id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $favorites = Epfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'type', 'updated_at')->where('episode_id', $id)->orderBy('updated_at')->paginate(20);
        return view('episode.favorites', ['episode' => $episode, 'drama' => $drama, 'favorites' => $favorites]);
    }

    public function sc($id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $roles = Role::with(['sc' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'sc_id', 'job', 'note')->where('episode_id', $id)->orderBy('job')->get();
        return view('episode.sc', ['episode' => $episode, 'drama' => $drama, 'roles' => $roles]);
    }

}
