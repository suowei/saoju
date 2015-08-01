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
        //
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

        $screv = new Screv;
        $screv->model = $request->input('model');
        $screv->model_id = $request->input('model_id');
        $screv->user_id = $request->user()->id;
        $screv->title = $request->input('title');
        $screv->content = $request->input('content');
        if($screv->save())
        {
            if($screv->model == 0)
                DB::table('scs')->where('id', $screv->model_id)->increment('reviews');
            else
                DB::table('clubs')->where('id', $screv->model_id)->increment('reviews');
            return redirect()->route('screv.show', [$screv]);
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

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
