<?php

namespace App\Http\Controllers;

use App\Ft;
use App\Ftep;
use App\Ftfav;
use App\Ftrev;
use App\Ftver;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FtController extends Controller
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
        if($request->has('host'))
        {
            $scope['host'] = ['LIKE', '%'.$request->input('host').'%'];
        }
        if($request->has('introduction'))
        {
            $scope['introduction'] = ['LIKE', '%'.$request->input('introduction').'%'];
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
        $fts = Ft::select('id', 'title', 'host', 'poster_url', 'introduction')
            ->multiwhere($scope)->orderBy($params['sort'], $params['order'])->paginate(30);
        return view('ft.index', ['params' => $params, 'fts' => $fts]);
    }

    public function create()
    {
        return view('ft.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'host' => 'max:255',
            'poster_url' => 'url',
        ]);

        if($ft = Ft::create(Input::all()))
        {
            Ftver::create(['ft_id' => $ft->id, 'user_id' => $request->user()->id, 'first' => 1, 'title' => $ft->title,
                'host' => $ft->host, 'poster_url' => $ft->poster_url, 'introduction' => $ft->introduction]);
            return redirect()->route('ft.show', [$ft]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $ft = Ft::find($id, ['id', 'title', 'host', 'poster_url', 'introduction', 'reviews', 'favorites']);
        $fteps = Ftep::select('id', 'title', 'release_date', 'url', 'staff', 'poster_url')
            ->where('ft_id', $id)->orderByRaw('release_date, id')->get();
        $reviews = Ftrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'ftep' => function($query) {
            $query->select('id', 'title');
        }])->select('id', 'ftep_id', 'user_id', 'title', 'content', 'created_at')
            ->where('ft_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Ftfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')->where('ft_id', $id)->orderBy('created_at', 'desc')->take(10)->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Ftfav::selectRaw(1)->where('user_id', $user_id)->where('ft_id', $id)->first();
            $userReviews = Ftrev::with(['ftep' => function($query) {
                $query->select('id', 'title');
            }])->select('id', 'ftep_id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('ft_id', $id)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('ft.show', ['ft' => $ft, 'fteps' => $fteps, 'reviews' => $reviews, 'favorites' => $favorites,
            'favorite' => $favorite, 'userReviews' => $userReviews]);
    }

    public function edit($id)
    {
        $ft = Ft::find($id, ['id', 'title', 'host', 'poster_url', 'introduction']);
        return view('ft.edit', ['ft' => $ft]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'host' => 'max:255',
            'poster_url' => 'url',
        ]);

        $ft = Ft::find($id);
        $ft->title = $request->input('title');
        $ft->host = $request->input('host');
        $ft->poster_url = $request->input('poster_url');
        $ft->introduction = $request->input('introduction');

        if($ft->save())
        {
            $user_id = $request->user()->id;
            $version = Ftver::where('ft_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Ftver;
                $version->ft_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->title = $ft->title;
            $version->host = $ft->host;
            $version->poster_url = $ft->poster_url;
            $version->introduction = $ft->introduction;
            $version->save();

            return redirect()->route('ft.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $version = Ftver::select('user_id')->where('ft_id', $id)->where('first', 1)->first();
        if($version->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除FT> <';
        }
        $ftep = Ftep::select('id')->where('ft_id', $id)->first();
        if($ftep)
        {
            return '抱歉，请先逐一删除分集FT后再删除本FT';
        }
        $favorite = Ftfav::select('user_id')->where('ft_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏FT，不能删除> <';
        }
        $review = Ftrev::select('id')->where('ft_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论FT，不能删除> <';
        }
        $ft = Ft::find($id, ['id']);
        if($ft->delete())
        {
            return redirect('/');
        }
        else
        {
            return '删除失败';
        }
    }

    public function versions($id)
    {
        $ft = Ft::find($id, ['id', 'title']);
        $versions = Ftver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'title', 'host', 'poster_url', 'introduction', 'created_at', 'updated_at')
            ->where('ft_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('ft.versions', ['ft' => $ft, 'versions' => $versions]);
    }

    public function reviews($id)
    {
        $ft = Ft::find($id, ['id', 'title']);
        $reviews = Ftrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')->where('ft_id', $id)->paginate(20);
        return view('ft.reviews', ['ft' => $ft, 'reviews' => $reviews]);
    }

    public function favorites($id)
    {
        $ft = Ft::find($id, ['id', 'title']);
        $favorites = Ftfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')->where('ft_id', $id)->orderBy('created_at')->paginate(20);
        return view('ft.favorites', ['ft' => $ft, 'favorites' => $favorites]);
    }
}
