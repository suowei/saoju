<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth, DB;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //获取登录用户所有待听列表及相应分集信息
        $playlists = Playlist::with(['episode' => function($query)
        {
            $query->join('dramas', 'dramas.id', '=', 'episodes.drama_id')
                ->select('episodes.id as id', 'drama_id', 'dramas.title as drama_title',
                    'episodes.title as episode_title', 'episodes.url as url');
        }])->where('user_id', Auth::id())->where('type', $request->input('type'))->orderBy('created_at', 'desc')->paginate(50);
        return view('user.playlist', ['type' => $request->input('type'), 'playlists' => $playlists]);
    }

    public function create(Request $request)
    {
        $playlist = new Playlist;
        $playlist->user_id = Auth::id();
        $playlist->episode_id = $request->input('episode');
        $playlist->save();
        return redirect()->back();
    }

    public function store()
    {
        //
    }

    public function show()
    {
        //
    }

    public function edit($episode_id)
    {
        //将状态修改为已听
        DB::table('playlists')->where('user_id', Auth::id())->where('episode_id', $episode_id)->update(['type' => 1]);
        return redirect()->back();
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
