<?php

namespace App\Http\Controllers;

use App\Song;
use App\Songfav;
use App\Songrev;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SongfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = Songfav::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'song' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('user_id', 'song_id', 'created_at')
            ->orderBy('created_at', 'desc')->take(50)->get();
        return view('songfav.index', ['favorites' => $favorites]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'song' => 'required',
        ]);
        $song = Song::find($request->input('song'), ['id', 'title']);
        return view('songfav.create', ['song' => $song]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'song' => 'required',
        ]);

        $favorite = new Songfav;
        $favorite->song_id = $request->input('song');
        $favorite->user_id = $request->user()->id;
        $favorite->created_at = new Carbon;
        if($favorite->save())
        {
            DB::table('songs')->where('id', $favorite->song_id)->increment('favorites');
            DB::table('users')->where('id', $favorite->user_id)->increment('songfavs');
        }
        return redirect()->back();
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'song_id' => 'required',
            'content' => 'required_with:title',
            'title' => 'max:255',
        ]);

        $favorite = new Songfav;
        $favorite->user_id = $request->user()->id;
        $favorite->song_id = $request->input('song_id');
        $favorite->created_at = new Carbon;
        if($request->has('content'))
        {
            $review = new Songrev;
            $review->user_id = $favorite->user_id;
            $review->song_id = $request->input('song_id');
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('users')->where('id', $review->user_id)->increment('songrevs');
                DB::table('songs')->where('id', $review->song_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加失败');
            }
        }
        if($favorite->save())
        {
            DB::table('users')->where('id', $favorite->user_id)->increment('songfavs');
            DB::table('songs')->where('id', $favorite->song_id)->increment('favorites');
            return redirect()->route('song.show', [$favorite->song_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function destroy(Request $request, $song_id)
    {
        $user_id = $request->user()->id;
        $result = DB::table('songfavs')->where('user_id', $user_id)->where('song_id', $song_id)->delete();
        if($result)
        {
            DB::table('users')->where('id', $user_id)->decrement('songfavs');
            DB::table('songs')->where('id', $song_id)->decrement('favorites');
        }
        return redirect()->back();
    }
}
