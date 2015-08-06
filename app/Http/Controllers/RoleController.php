<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Episode;
use App\Role;
use App\Sc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $episode = Episode::find($request->input('episode'), ['id', 'drama_id', 'title']);
        $episode->load(['drama' => function($query)
        {
            $query->select('id', 'title');
        }]);
        return view('role.create', ['episode' => $episode]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'drama_id' => 'required',
            'episode_id' => 'required',
            'sc' => 'required',
            'job' => 'required',
        ]);

        $role = new Role;
        $role->drama_id = $request->input('drama_id');
        $role->episode_id = $request->input('episode_id');
        if($sc = Sc::select('id')->where('name', $request->input('sc'))->first())
        {
            $role->sc_id = $sc->id;
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('未找到该SC');
        }
        $role->job = $request->input('job');
        $role->note = $request->input('note');
        $role->user_id = $request->user()->id;
        if($role->save())
        {
            return redirect()->route('episode.sc', [$role->episode_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $role = Role::find($id, ['id', 'drama_id', 'episode_id', 'sc_id', 'job', 'note']);
        $drama = Drama::find($role->drama_id, ['title']);
        $episode = Episode::find($role->episode_id, ['title']);
        $sc = Sc::find($role->sc_id, ['name']);
        return view('role.edit', ['role' => $role, 'drama' => $drama, 'episode' => $episode, 'sc' => $sc]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'sc' => 'required',
            'job' => 'required',
        ]);

        $role = Role::find($id);
        if($sc = Sc::select('id')->where('name', $request->input('sc'))->first())
        {
            $role->sc_id = $sc->id;
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('未找到该SC');
        }
        $role->job = $request->input('job');
        $role->note = $request->input('note');
        $role->user_id = $request->user()->id;
        if($role->save())
        {
            return redirect()->route('episode.sc', [$role->episode_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('episode.sc', [$role->episode_id]);
    }
}
