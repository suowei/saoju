<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Ed;
use App\Episode;
use App\Song;
use App\Songfav;
use App\Songrev;
use App\Songver;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Input;

class SongController extends Controller
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
        if($request->has('artist'))
        {
            $scope['artist'] = ['LIKE', '%'.$request->input('artist').'%'];
        }
        if($request->has('staff'))
        {
            $scope['staff'] = ['LIKE', '%'.$request->input('staff').'%'];
        }
        if($request->has('lyrics'))
        {
            $scope['lyrics'] = ['LIKE', '%'.$request->input('lyrics').'%'];
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
        $songs = Song::select('id', 'title', 'alias', 'artist', 'staff')
            ->multiwhere($scope)->orderBy($params['sort'], $params['order'])->paginate(30);
        return view('song.index', ['params' => $params, 'songs' => $songs]);
    }

    public function create(Request $request)
    {
        if($request->has('drama'))
            $drama = $request->input('drama');
        else
            $drama = 0;
        if($request->has('episode'))
            $episode = $request->input('episode');
        else
            $episode = 0;
        return view('song.create', ['drama' => $drama, 'episode' => $episode]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'alias' => 'max:255',
            'artist' => 'required|max:255',
            'url' => 'url',
            'poster_url' => 'url',
            'staff' => 'required',
        ]);

        if($song = Song::create(Input::all()))
        {
            Songver::create(['song_id' => $song->id, 'user_id' => $request->user()->id, 'first' => 1,
                'title' => $song->title, 'alias' => $song->alias, 'artist' => $song->artist, 'url' => $song->url,
                'poster_url' => $song->poster_url, 'staff' => $song->staff, 'lyrics' => $song->lyrics]);
            if($request->has('drama_id'))
                Ed::create(['drama_id' => $request->input('drama_id'), 'episode_id' =>
                    ($request->has('episode_id') ? $request->input('episode_id') : 0),
                    'song_id' => $song->id, 'user_id' => $request->user()->id]);
            return redirect()->route('song.show', [$song]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $song = Song::find($id, ['id', 'title', 'alias', 'artist', 'url',
            'poster_url', 'staff', 'lyrics', 'reviews', 'favorites']);
        $reviews = Songrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('song_id', $id)->orderBy('id', 'desc')->take(20)->get();
        $favorites = Songfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')
            ->where('song_id', $id)->orderBy('created_at', 'desc')->take(10)->get();
        $eds = Ed::with(['drama' => function($query) {
            $query->select('id', 'title');
        }, 'episode' => function($query) {
            $query->select('id', 'title');
        }])->where('song_id', $id)->get();
        if(Auth::check())
        {
            $user_id = $request->user()->id;
            $favorite = Songfav::selectRaw(1)->where('user_id', $user_id)->where('song_id', $id)->first();
            $userReviews = Songrev::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $user_id)->where('song_id', $id)->get();
        }
        else
        {
            $favorite = 0;
            $userReviews = 0;
        }
        return view('song.show', ['song' => $song, 'eds' => $eds,
            'reviews' => $reviews, 'favorites' => $favorites,
            'favorite' => $favorite, 'userReviews' => $userReviews]);
    }

    public function edit($id)
    {
        $song = Song::find($id, ['id', 'title', 'alias', 'artist', 'url', 'poster_url', 'staff', 'lyrics']);
        return view('song.edit', ['song' => $song]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'alias' => 'max:255',
            'artist' => 'required|max:255',
            'url' => 'url',
            'poster_url' => 'url',
            'staff' => 'required',
        ]);

        $song = Song::find($id);
        $song->title = $request->input('title');
        $song->alias = $request->input('alias');
        $song->artist = $request->input('artist');
        $song->url = $request->input('url');
        $song->poster_url = $request->input('poster_url');
        $song->staff = $request->input('staff');
        $song->lyrics = $request->input('lyrics');

        if($song->save())
        {
            $user_id = $request->user()->id;
            $version = Songver::where('song_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Songver;
                $version->song_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->title = $song->title;
            $version->alias = $song->alias;
            $version->artist = $song->artist;
            $version->url = $song->url;
            $version->poster_url = $song->poster_url;
            $version->staff = $song->staff;
            $version->lyrics = $song->lyrics;
            $version->save();

            return redirect()->route('song.show', [$id]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $version = Songver::select('user_id')->where('song_id', $id)->where('first', 1)->first();
        if($version->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除歌曲> <';
        }
        $favorite = Songfav::select('user_id')->where('song_id', $id)->first();
        if($favorite)
        {
            return '抱歉, 已有人收藏歌曲，不能删除> <';
        }
        $review = Songrev::select('id')->where('song_id', $id)->first();
        if($review)
        {
            return '抱歉，已有人评论歌曲，不能删除> <';
        }
        $ed = Ed::select('id')->where('song_id', $id)->first();
        if($ed)
        {
            return '抱歉，请先逐一删除歌曲关联后再删除歌曲';
        }
        $song = Song::find($id, ['id']);
        if($song->delete())
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
        $song = Song::find($id, ['id', 'title']);
        $versions = Songver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'title', 'alias', 'artist', 'url',
                'poster_url', 'staff', 'lyrics', 'created_at', 'updated_at')
            ->where('song_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('song.versions', ['song' => $song, 'versions' => $versions]);
    }

    public function reviews($id)
    {
        $song = Song::find($id, ['id', 'title']);
        $reviews = Songrev::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')->where('song_id', $id)->paginate(20);
        return view('song.reviews', ['song' => $song, 'reviews' => $reviews]);
    }

    public function favorites($id)
    {
        $song = Song::find($id, ['id', 'title']);
        $favorites = Songfav::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('user_id', 'created_at')->where('song_id', $id)->orderBy('created_at')->paginate(20);
        return view('song.favorites', ['song' => $song, 'favorites' => $favorites]);
    }
}
