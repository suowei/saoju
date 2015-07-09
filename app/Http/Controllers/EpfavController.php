<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Epfav;
use App\Episode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EpfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'episode' => 'required',
        ]);
        $episode = Episode::find($request->input('episode'), ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        return view('epfav.create', ['episode' => $episode, 'drama' => $drama]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:0,1,2',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'episode_id' => 'required',
        ]);

        $epfav = new Epfav;
        $epfav->user_id = $request->user()->id;
        $epfav->episode_id = $request->input('episode');
        $epfav->type = $request->input('type');
        if($epfav->type == 0)//想听状态下不能评分
        {
            $epfav->rating = 0;
        }
        else
        {
            $epfav->rating = $request->input('rating');
        }
        if($epfav->save())
        {
            DB::table('users')->where('id', $epfav->user_id)->increment('epfav'.$epfav->type);
            DB::table('episodes')->where('id', $epfav->episode_id)->increment('favorites');
        }
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $episode_id)
    {
        $this->validate($request, [
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        $favorite = Epfav::where('user_id', $request->user()->id)->where('episode_id', $episode_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $result = DB::table('epfavs')->where('user_id', $favorite->user_id)->where('episode_id', $episode_id)
            ->update(['type' => 1, 'rating' => $favorite->rating, 'updated_at' => date("Y-m-d H:i:s")]);
        if($result)
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('epfav'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('epfav'.$favorite->type);
        }
        return redirect()->back();
    }

    public function destroy(Request $request, $episode_id)
    {
        DB::table('epfavs')->where('user_id', $request->user()->id)->where('episode_id', $episode_id)->delete();
        return redirect()->back();
    }
}
