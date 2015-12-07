<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Ed;
use App\Episode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function create(Request $request)
    {
        $drama = Drama::find($request->input('drama'), ['id', 'title']);
        if($request->has('episode'))
            $episode = Episode::find($request->input('episode'), ['id', 'title']);
        else
            $episode = null;
        return view('ed.create', ['drama' => $drama, 'episode' => $episode]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'drama_id' => 'required',
            'episode_id' => 'required',
            'song' => 'required',
        ]);

        $ed = new Ed;
        $ed->drama_id = $request->input('drama_id');
        $ed->episode_id = $request->input('episode_id');
        $ed->song_id = $request->input('song');
        $ed->user_id = $request->user()->id;
        if($ed->save())
        {
            if($ed->episode_id)
                return redirect()->route('episode.songs', [$ed->episode_id]);
            else
                return redirect()->route('drama.songs', [$ed->drama_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function edit(Request $request, $id)
    {
        $ed = Ed::find($id, ['id', 'drama_id', 'episode_id', 'song_id', 'user_id']);
        if($ed->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户编辑关联信息> <';
        }
        return view('ed.edit', ['ed' => $ed]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'drama' => 'required',
            'episode' => 'required',
            'song' => 'required',
        ]);

        $ed = Ed::find($id);
        $ed->drama_id = $request->input('drama');
        $ed->episode_id = $request->input('episode');
        $ed->song_id = $request->input('song');
        $ed->user_id = $request->user()->id;
        if($ed->save())
        {
            if($ed->episode_id)
                return redirect()->route('episode.songs', [$ed->episode_id]);
            else
                return redirect()->route('drama.songs', [$ed->drama_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $ed = Ed::find($id);
        if($ed->user_id != $request->user()->id)
        {
            return '抱歉, 目前仅支持添加此条目的用户删除关联> <';
        }
        $ed->delete();
        if($ed->episode_id)
            return redirect()->route('episode.songs', [$ed->episode_id]);
        else
            return redirect()->route('drama.songs', [$ed->drama_id]);
    }
}
