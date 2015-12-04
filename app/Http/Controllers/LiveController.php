<?php

namespace App\Http\Controllers;

use App\Live;
use App\Livefav;
use App\Liverev;
use App\Livever;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Input;

class LiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        $scope = [];
        if($request->has('title'))
        {
            $scope['title'] = ['LIKE', '%'.$request->input('title').'%'];
        }
        if($request->has('information'))
        {
            $scope['information'] = ['LIKE', '%'.$request->input('information').'%'];
        }
        if($request->has('startdate') || $request->has('enddate'))
        {
            if(!$request->has('startdate'))
                $scope['showtime'] = ['<=', $request->input('enddate').' 23:59:59'];
            else if(!$request->has('enddate'))
                $scope['showtime'] = ['>=', $request->input('startdate').' 00:00:00'];
            else
                $showtime = [$request->input('startdate').' 00:00:00', $request->input('enddate').' 23:59:59'];
        }
        $params = $request->except('page');
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
        if(isset($showtime))
        {
            $lives = Live::select('id', 'title', 'showtime', 'information')
                ->whereBetween('showtime', $showtime)
                ->multiwhere($scope)
                ->orderBy($params['sort'], $params['order'])
                ->paginate(30);
        }
        else
        {
            $lives = Live::select('id', 'title', 'showtime', 'information')
                ->multiwhere($scope)
                ->orderBy($params['sort'], $params['order'])
                ->paginate(30);
        }
        return view('live.index', ['params' => $params, 'lives' => $lives]);
    }

    public function create()
    {
        return view('live.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'showtime' => 'required|date',
            'poster_url' => 'url',
            'record_url' => 'url',
        ]);

        if($live = Live::create(Input::all()))
        {
            Livever::create(['live_id' => $live->id, 'user_id' => $request->user()->id, 'first' => 1,
                'title' => $live->title, 'showtime' => $live->showtime, 'information' => $live->information,
                'poster_url' => $live->poster_url, 'record_url' => $live->record_url]);
            return redirect()->route('live.show', [$live]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $live = Live::find($id, ['id', 'title', 'showtime', 'information', 'poster_url', 'record_url', 'reviews', 'favorites']);
        $reviews = Liverev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('live_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Livefav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')
            ->where('live_id', $id)->orderBy('created_at', 'desc')->take(10)->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Livefav::selectRaw(1)->where('user_id', $user_id)->where('live_id', $id)->first();
            $userReviews = Liverev::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('live_id', $id)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('live.show', ['live' => $live, 'reviews' => $reviews, 'favorites' => $favorites,
            'favorite' => $favorite, 'userReviews' => $userReviews]);
    }

    public function edit($id)
    {
        $live = Live::find($id, ['id', 'title', 'showtime', 'information', 'poster_url', 'record_url']);
        return view('live.edit', ['live' => $live]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'showtime' => 'required|date',
            'poster_url' => 'url',
            'record_url' => 'url',
        ]);

        $live = Live::find($id);
        $live->title = $request->input('title');
        $live->showtime = $request->input('showtime');
        $live->information = $request->input('information');
        $live->poster_url = $request->input('poster_url');
        $live->record_url = $request->input('record_url');

        if($live->save())
        {
            $user_id = $request->user()->id;
            $version = Livever::where('live_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Livever;
                $version->live_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->title = $live->title;
            $version->showtime = $live->showtime;
            $version->information = $live->information;
            $version->poster_url = $live->poster_url;
            $version->record_url = $live->record_url;
            $version->save();

            return redirect()->route('live.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $version = Livever::select('user_id')->where('live_id', $id)->where('first', 1)->first();
        if($version->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除活动> <';
        }
        $favorite = Songfav::select('user_id')->where('live_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏活动，不能删除> <';
        }
        $review = Songrev::select('id')->where('live_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论活动，不能删除> <';
        }
        $live = Live::find($id, ['id']);
        if($live->delete())
        {
            return redirect('/zhoubian');
        }
        else
        {
            return '删除失败';
        }
    }

    public function versions($id)
    {
        $live = Live::find($id, ['id', 'title']);
        $versions = Livever::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'title', 'showtime', 'information', 'poster_url',
                'record_url', 'created_at', 'updated_at')
            ->where('live_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('live.versions', ['live' => $live, 'versions' => $versions]);
    }

    public function reviews($id)
    {
        $live = Live::find($id, ['id', 'title']);
        $reviews = Liverev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')->where('live_id', $id)->paginate(20);
        return view('live.reviews', ['live' => $live, 'reviews' => $reviews]);
    }

    public function favorites($id)
    {
        $live = Live::find($id, ['id', 'title']);
        $favorites = Livefav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')->where('live_id', $id)->orderBy('created_at')->paginate(20);
        return view('live.favorites', ['live' => $live, 'favorites' => $favorites]);
    }
}
