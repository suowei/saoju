<?php

namespace App\Http\Controllers;

use App\Club;
use App\Sc;
use App\Screv;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScrevController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $reviews = Screv::leftJoin('scs', function($join)
        {
            $join->on('screvs.model_id', '=', 'scs.id')
                ->where('screvs.model', '=', 0);
        })
            ->leftJoin('clubs', function($join)
        {
            $join->on('screvs.model_id', '=', 'clubs.id')
                ->where('screvs.model', '=', 1);
        })
            ->select('screvs.*', 'scs.name as sc_name', 'clubs.name as club_name')
            ->orderBy('id', 'desc')->paginate(20);
        $reviews->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        return view('screv.index', ['reviews' => $reviews]);
    }

    public function create(Request $request)
    {
        if($request->has('club'))
        {
            $club = Club::find($request->input('club'), ['id', 'name']);
            return view('screv.create', ['model' => 1, 'model_id' => $club->id, 'model_name' => $club->name]);
        }
        else if($request->has('sc'))
        {
            $sc = Sc::find($request->input('sc'), ['id', 'name']);
            return view('screv.create', ['model' => 0, 'model_id' => $sc->id, 'model_name' => $sc->name]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'model' => 'required',
            'model_id' => 'required',
            'content' => 'required',
            'title' => 'max:255',
        ]);

        $review = new Screv;
        $review->model = $request->input('model');
        $review->model_id = $request->input('model_id');
        $review->user_id = $request->user()->id;
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        if($review->save())
        {
            DB::table('users')->where('id', $review->user_id)->increment('screvs');
            if($review->model == 0)
                DB::table('scs')->where('id', $review->model_id)->increment('reviews');
            else
                DB::table('clubs')->where('id', $review->model_id)->increment('reviews');
            return redirect()->route('screv.show', [$review]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $screv = Screv::find($id);
        if(!$screv)
            return redirect()->to('/');
        $screv->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        if($screv->model == 0)
        {
            $model = Sc::find($screv->model_id, ['id', 'name']);
        }
        else
        {
            $model = Club::find($screv->model_id, ['id', 'name']);
        }
        return view('screv.show', ['review' => $screv, 'model' => $model]);
    }

    public function edit(Request $request, $id)
    {
        $review = Screv::find($id);
        if($review->user_id == $request->user()->id)
        {
            if($review->model == 0)
            {
                $model = Sc::find($review->model_id, ['name']);
            }
            else
            {
                $model = Club::find($review->model_id, ['name']);
            }
            return view('screv.edit', ['review' => $review, 'model' => $model]);
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

        $review = Screv::find($id);
        $review->title = $request->input('title');
        $review->content = $request->input('content');

        if($review->save())
        {
            return redirect()->route('screv.show', [$review]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $review = Screv::find($id);
        if ($review->user_id == $request->user()->id)
        {
            if($review->delete())
            {
                DB::table('users')->where('id', $review->user_id)->decrement('screvs');
                if($review->model == 0)
                    DB::table('scs')->where('id', $review->model_id)->decrement('reviews');
                else
                    DB::table('clubs')->where('id', $review->model_id)->decrement('reviews');
            }
        }
        return redirect()->back();
    }
}
