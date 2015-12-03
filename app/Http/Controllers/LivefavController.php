<?php

namespace App\Http\Controllers;

use App\Live;
use App\Livefav;
use App\Liverev;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LivefavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index()
    {
        $favorites = Livefav::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->select('user_id', 'live_id', 'created_at')
            ->orderBy('created_at', 'desc')->take(50)->get();
        return view('livefav.index', ['favorites' => $favorites]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'live' => 'required',
        ]);
        $live = Live::find($request->input('live'), ['id', 'title']);
        return view('livefav.create', ['live' => $live]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'live' => 'required',
        ]);

        $favorite = new Livefav;
        $favorite->live_id = $request->input('live');
        $favorite->user_id = $request->user()->id;
        $favorite->created_at = new Carbon;
        if($favorite->save())
        {
            DB::table('lives')->where('id', $favorite->live_id)->increment('favorites');
        }
        return redirect()->back();
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'live_id' => 'required',
            'content' => 'required_with:title',
            'title' => 'max:255',
        ]);

        $favorite = new Livefav;
        $favorite->user_id = $request->user()->id;
        $favorite->live_id = $request->input('live_id');
        $favorite->created_at = new Carbon;
        if($request->has('content'))
        {
            $review = new Liverev;
            $review->user_id = $favorite->user_id;
            $review->live_id = $request->input('live_id');
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('lives')->where('id', $review->live_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加失败');
            }
        }
        if($favorite->save())
        {
            DB::table('lives')->where('id', $favorite->live_id)->increment('favorites');
            return redirect()->route('live.show', [$favorite->live_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function destroy(Request $request, $live_id)
    {
        $result = DB::table('livefavs')->where('user_id', $request->user()->id)->where('live_id', $live_id)->delete();
        if($result)
        {
            DB::table('lives')->where('id', $live_id)->decrement('favorites');
        }
        return redirect()->back();
    }
}
