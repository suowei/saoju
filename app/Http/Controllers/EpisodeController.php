<?php namespace App\Http\Controllers;

use App\Episode;
use App\Review;
use App\History;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Input, Auth;

class EpisodeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'reviews', 'histories']]);
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
        //日期筛选
        if($request->has('year'))
        {
            if($request->has('month'))
            {
                if($request->has('day'))
                {
                    $scope['release_date'] = ['=', $request->input('year').'-'.$request->input('month').'-'.$request->input('day')];
                }
                else
                {
                    $release_date = [
                        $request->input('year').'-'.$request->input('month').'-01',
                        $request->input('year').'-'.$request->input('month').'-31'
                    ];
                }
            }
            else
            {
                $release_date = [
                    $request->input('year').'-01-01',
                    $request->input('year').'-12-31'
                ];
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
        return view('episode.index')->with('params', $params)->withEpisodes($episodes);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        return view('episode.create')->withDrama($request->input('drama'));
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
            'drama_id' => 'required|exists:dramas,id',
        ]);

        $history = new History;
        $history->user_id = Auth::id();
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
	public function show($id)
	{
        $episode = Episode::find($id);
        $drama = $episode->drama;
        $reviews = Review::with('user', 'episode')->where('episode_id', $id)->orderBy('created_at', 'desc')->take(20)->get();
		return view('episode.show')->withEpisode($episode)->withDrama($drama)->withReviews($reviews);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return view('episode.edit')->withEpisode(Episode::find($id));
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
        $history->user_id = Auth::id();
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    public function reviews($id)
    {
        $episode = Episode::with('drama')->find($id);
        $reviews = Review::with('user')->where('episode_id', $id)->paginate(20);
        return view('episode.reviews')->withEpisode($episode)->withReviews($reviews);
    }

    public function  histories($id)
    {
        $episode = Episode::find($id);
        $histories = History::where('model', 1)->where('model_id', $id)->get();
        return view('episode.histories')->withEpisode($episode)->withHistories($histories);
    }

}
