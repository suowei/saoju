<?php

namespace App\Http\Controllers;

use App\Ft;
use App\Ftep;
use App\Ftrev;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FtrevController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Ftrev::with(['user' => function($query)
        {
            $query->select('id','name');
        }, 'ft' => function($query)
        {
            $query->select('id', 'title');
        }, 'ftep' => function($query)
        {
            $query->select('id', 'title');
        }])->orderBy('id', 'desc')->paginate(20);
        return view('ftrev.index', ['reviews' => $reviews]);
    }

    public function create(Request $request)
    {
        if($request->has('ftep'))
            return view('ftrev.create', ['ft' => Ft::find($request->input('ft'), ['id', 'title']),
                'ftep' => Ftep::find($request->input('ftep'), ['id', 'title'])]);
        else
            return view('ftrev.create', ['ft' => Ft::find($request->input('ft'), ['id', 'title'])]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ft_id' => 'required',
            'content' => 'required',
            'title' => 'max:255',
        ]);

        $review = new Ftrev;
        $review->song_id = $request->input('song_id');
        $review->user_id = $request->user()->id;
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        if($review->save())
        {
            DB::table('fts')->where('id', $review->ft_id)->increment('reviews');
            if($review->ftep_id)
                DB::table('fteps')->where('id', $review->ftep_id)->increment('reviews');
            return redirect()->route('ftrev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $review = Ftrev::find($id);
        if(!$review)
            return redirect()->to('/zhoubian');
        $review->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $review->load(['ft' => function($query)
        {
            $query->select('id', 'title');
        }]);
        if($review->ftep_id)
        {
            $review->load(['ftep' => function($query)
            {
                $query->select('id', 'title');
            }]);
        }
        return view('ftrev.show', ['review' => $review]);
    }

    public function edit(Request $request, $id)
    {
        $review = Ftrev::find($id);
        if($review->user_id == $request->user()->id)
        {
            $review->load(['ft' => function($query)
            {
                $query->select('id', 'title');
            }]);
            if($review->ftep_id)
            {
                $review->load(['ftep' => function($query)
                {
                    $query->select('id', 'title');
                }]);
            }
            return view('ftrev.edit', ['review' => $review]);
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

        $review = Ftrev::find($id);
        $review->title = $request->input('title');
        $review->content = $request->input('content');

        if($review->save())
        {
            return redirect()->route('ftrev.show', [$review]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $review = Ftrev::find($id);
        if ($review->user_id == $request->user()->id)
        {
            if($review->delete())
            {
                DB::table('fts')->where('id', $review->ft_id)->decrement('reviews');
                if($review->ftep_id)
                    DB::table('fteps')->where('id', $review->ftep_id)->decrement('reviews');
            }
        }
        return redirect()->back();
    }
}
