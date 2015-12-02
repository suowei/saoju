<?php

namespace App\Http\Controllers;

use App\Ft;
use App\Ftep;
use App\Ftepfav;
use App\Ftepver;
use App\Ftrev;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Input;

class FtepController extends Controller
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
            $scope['fts.title'] = ['LIKE', '%'.$request->input('title').'%'];
        }
        if($request->has('host'))
        {
            $scope['fts.host'] = ['LIKE', '%'.$request->input('host').'%'];
        }
        if($request->has('staff'))
        {
            $scope['staff'] = ['LIKE', '%'.$request->input('staff').'%'];
        }
        if($request->has('startdate') || $request->has('enddate'))
        {
            if(!$request->has('startdate'))
                $scope['release_date'] = ['<=', $request->input('enddate')];
            else if(!$request->has('enddate'))
                $scope['release_date'] = ['>=', $request->input('startdate')];
            else
                $release_date = [$request->input('startdate'), $request->input('enddate')];
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
        if(isset($release_date))
        {
            $fteps = Ftep::whereBetween('release_date', $release_date)
                ->join('fts', function($join) use($scope)
                {
                    $join->on('fteps.ft_id', '=', 'fts.id');
                    foreach($scope as $key => $value)
                    {
                        $join = $join->where($key, $value[0], $value[1]);
                    }
                })
                ->select('fteps.*', 'fts.id as ft_id', 'fts.title as ft_title', 'fts.host as host')
                ->orderBy('fteps.'.$params['sort'], $params['order'])->paginate(20);
        }
        else
        {
            $fteps = Ftep::join('fts', function($join) use($scope)
            {
                $join->on('fteps.ft_id', '=', 'fts.id');
                foreach($scope as $key => $value)
                {
                    $join = $join->where($key, $value[0], $value[1]);
                }
            })
                ->select('fteps.*', 'fts.id as ft_id', 'fts.title as ft_title', 'fts.host as host')
                ->orderBy('fteps.'.$params['sort'], $params['order'])->paginate(20);
        }
        return view('ftep.index', ['params'=> $params, 'fteps' => $fteps]);
    }

    public function create(Request $request)
    {
        $ft = Ft::find($request->input('ft'), ['id', 'title']);
        return view('ftep.create', ['ft' => $ft]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ft_id' => 'required',
            'title' => 'required|max:255',
            'release_date' => 'required|date',
            'url' => 'url',
            'staff' => 'required',
            'poster_url' => 'url',
        ]);

        if($ftep = Ftep::create(Input::all()))
        {
            Ftepver::create(['ftep_id' => $ftep->id, 'user_id' => $request->user()->id, 'first' => 1,
                'title' => $ftep->title, 'release_date' => $ftep->release_date,
                'url' => $ftep->url, 'staff' => $ftep->staff, 'poster_url' => $ftep->poster_url]);
            return redirect()->route('ftep.show', [$ftep]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $ftep = Ftep::find($id, ['id', 'ft_id', 'title', 'release_date', 'url', 'staff',
            'poster_url', 'reviews', 'favorites']);
        $ft = Ft::find($ftep->ft_id, ['title']);
        $reviews = Ftrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('ftep_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Ftepfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')
            ->where('ftep_id', $id)->orderBy('created_at', 'desc')->take(10)->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Ftepfav::selectRaw(1)->where('user_id', $user_id)->where('ftep_id', $id)->first();
            $userReviews = Ftrev::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('ftep_id', $id)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('ftep.show', ['ftep' => $ftep, 'ft' => $ft, 'reviews' => $reviews,
            'favorites' => $favorites, 'favorite' => $favorite, 'userReviews' => $userReviews]);
    }

    public function edit($id)
    {
        $ftep = Ftep::find($id, ['id', 'ft_id', 'title', 'release_date', 'url', 'staff', 'poster_url']);
        $ft = Ft::find($ftep->ft_id, ['title']);
        return view('ftep.edit', ['ftep' => $ftep, 'ft' => $ft]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'release_date' => 'required|date',
            'url' => 'url',
            'staff' => 'required',
            'poster_url' => 'url',
        ]);

        $ftep = Ftep::find($id);
        $ftep->title = $request->input('title');
        $ftep->release_date = $request->input('release_date');
        $ftep->url = $request->input('url');
        $ftep->staff = $request->input('staff');
        $ftep->poster_url = $request->input('poster_url');

        if($ftep->save())
        {
            $user_id = $request->user()->id;
            $version = Ftepver::where('ftep_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Ftepver;
                $version->ftep_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->title = $ftep->title;
            $version->release_date = $ftep->release_date;
            $version->url = $ftep->url;
            $version->staff = $ftep->staff;
            $version->poster_url = $ftep->poster_url;
            $version->save();

            return redirect()->route('ftep.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $version = Ftepver::select('user_id')->where('ftep_id', $id)->where('first', 1)->first();
        if($version->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除节目分集> <';
        }
        $favorite = Ftepfav::select('user_id')->where('ftep_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏本期节目，不能删除> <';
        }
        $review = Ftrev::select('id')->where('ftep_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论本期节目，不能删除> <';
        }
        $ftep = Ftep::find($id, ['id', 'ft_id']);
        if($ftep->delete())
        {
            return redirect()->route('ft.show', [$ftep->ft_id]);
        }
        else
        {
            return '删除失败';
        }
    }

    public function versions($id)
    {
        $ftep = Ftep::find($id, ['id', 'ft_id', 'title']);
        $ft = Ft::find($ftep->ft_id, ['id', 'title']);
        $versions = Ftepver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'title', 'release_date', 'url', 'staff', 'poster_url', 'created_at', 'updated_at')
            ->where('ftep_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('ftep.versions', ['ftep' => $ftep, 'ft' => $ft, 'versions' => $versions]);
    }

    public function reviews($id)
    {
        $ftep = Ftep::find($id, ['id', 'ft_id', 'title']);
        $ft = Ft::find($ftep->ft_id, ['id', 'title']);
        $reviews = Ftrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')->where('ftep_id', $id)->paginate(20);
        return view('ftep.reviews', ['ftep' => $ftep, 'ft' => $ft, 'reviews' => $reviews]);
    }

    public function favorites($id)
    {
        $ftep = Ftep::find($id, ['id', 'ft_id', 'title']);
        $ft = Ft::find($ftep->ft_id, ['id', 'title']);
        $favorites = Ftepfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')->where('ftep_id', $id)->orderBy('created_at')->paginate(20);
        return view('ftep.favorites', ['ftep' => $ftep, 'ft' => $ft, 'favorites' => $favorites]);
    }
}
