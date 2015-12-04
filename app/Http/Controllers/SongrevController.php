<?php

namespace App\Http\Controllers;

use App\Song;
use App\Songrev;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Illuminate\Support\Facades\DB;

class SongrevController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Songrev::orderBy('id', 'desc')->paginate(20);
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $reviews->load(['song' => function($query)
        {
            $query->select('id', 'title');
        }]);
        return view('songrev.index', ['reviews' => $reviews]);
    }

    public function create(Request $request)
    {
        $song = Song::find($request->input('song'), ['id', 'title']);
        return view('songrev.create', ['song' => $song]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'song_id' => 'required',
            'content' => 'required',
            'title' => 'max:255',
        ]);

        $review = new Songrev;
        $review->song_id = $request->input('song_id');
        $review->user_id = $request->user()->id;
        $review->title = $request->input('title');
        $review->content = $request->input('content');
       if($review->save())
        {
            DB::table('songs')->where('id', $review->song_id)->increment('reviews');
            return redirect()->route('songrev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $songrev = Songrev::find($id);
        if(!$songrev)
            return redirect()->to('/zhoubian');
        $songrev->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $songrev->load(['song' => function($query)
        {
            $query->select('id', 'title');
        }]);
        return view('songrev.show', ['review' => $songrev]);
    }

    public function edit(Request $request, $id)
    {
        $review = Songrev::find($id);
        if($review->user_id == $request->user()->id)
        {
            $review->load(['song' => function($query)
            {
                $query->select('id', 'title');
            }]);
            return view('songrev.edit', ['review' => $review]);
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

        $review = Songrev::find($id);
        $review->title = $request->input('title');
        $review->content = $request->input('content');

        if($review->save())
        {
            return redirect()->route('songrev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $review = Songrev::find($id);
        if ($review->user_id == $request->user()->id)
        {
            if($review->delete())
            {
                DB::table('songs')->where('id', $review->song_id)->decrement('reviews');
            }
        }
        return redirect()->back();
    }
}
