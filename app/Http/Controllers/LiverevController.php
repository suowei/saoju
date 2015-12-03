<?php

namespace App\Http\Controllers;

use App\Live;
use App\Liverev;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Illuminate\Support\Facades\DB;

class LiverevController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Liverev::orderBy('id', 'desc')->paginate(20);
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }]);
        return view('liverev.index', ['reviews' => $reviews]);
    }

    public function create(Request $request)
    {
        $live = Live::find($request->input('live'), ['id', 'title']);
        return view('liverev.create', ['live' => $live]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'live_id' => 'required',
            'content' => 'required',
            'title' => 'max:255',
        ]);

        $review = new Liverev;
        $review->live_id = $request->input('live_id');
        $review->user_id = $request->user()->id;
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        if($review->save())
        {
            DB::table('lives')->where('id', $review->live_id)->increment('reviews');
            return redirect()->route('liverev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $review = Liverev::find($id);
        if(!$review)
            return redirect()->to('/zhoubian');
        $review->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $review->load(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }]);
        return view('liverev.show', ['review' => $review]);
    }

    public function edit(Request $request, $id)
    {
        $review = Liverev::find($id);
        if($review->user_id == $request->user()->id)
        {
            $review->load(['live' => function($query)
            {
                $query->select('id', 'title');
            }]);
            return view('liverev.edit', ['review' => $review]);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required',
            'title' => 'max:255',
        ]);

        $review = Liverev::find($id);
        $review->title = $request->input('title');
        $review->content = $request->input('content');

        if($review->save())
        {
            return redirect()->route('liverev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $review = Liverev::find($id);
        if ($review->user_id == $request->user()->id)
        {
            if($review->delete())
            {
                DB::table('lives')->where('id', $review->live_id)->decrement('reviews');
            }
        }
        return redirect()->back();
    }
}
