<?php namespace App\Http\Controllers;

use App\Drama;
use App\History;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Review;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Input;

class DramaController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'reviews', 'histories', 'search']]);
    }

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
            $dramas = Drama::orderBy('id', 'desc')->paginate(20);
        else
            $dramas = Drama::where('type', $type)->orderBy('id', 'desc')->paginate(20);
		return view('drama.index')->with('type', $type)->withDramas($dramas);
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
        $history->user_id = Auth::id();
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
	public function show($id)
	{
        $drama = Drama::find($id);
        $episodes = $drama->episodes->sortBy('release_date');
        $reviews = Review::with('user', 'episode')->where('drama_id', $id)->orderBy('created_at', 'desc')->take(20)->get();
        return view('drama.show')->withDrama($drama)->withEpisodes($episodes)->withReviews($reviews);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('drama.edit')->withDrama(Drama::find($id));
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
        $history->user_id = Auth::id();
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
        $drama = Drama::find($id);
        $reviews = Review::with('user', 'episode')->where('drama_id', $id)->paginate(20);
        return view('drama.reviews')->withDrama($drama)->withReviews($reviews);
    }

    public function histories($id)
    {
        $drama = Drama::find($id);
        $histories = History::where('model', 0)->where('model_id', $id)->get();
        return view('drama.histories')->withDrama($drama)->withHistories($histories);
    }

    public function search(Request $request)
    {
        if($request->input('title') != '')
        {
            $dramas = Drama::select('id', 'sc')->where('title', $request->input('title'))->get();
            return $dramas;
        }
    }

}
