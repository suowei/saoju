<?php

namespace App\Http\Controllers;

use App\Club;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::select('id', 'name')->get();
        return view('club.index', ['clubs' => $clubs]);
    }

    public function create()
    {
        return view('club.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $club = new Club;
        $club->name = $request->input('name');
        $club->information = $request->input('information');
        $club->user_id = $request->user()->id;
        if($club->save())
        {
            return redirect()->route('club.show', [$club]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $club = Club::find($id, ['id', 'name', 'information']);
        return view('club.show', ['club' => $club]);
    }

    public function edit($id)
    {
        $club = Club::find($id, ['id', 'name', 'information']);
        return view('club.edit', ['club' => $club]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $club = Club::find($id);
        $club->name = $request->input('name');
        $club->information = $request->input('information');
        $club->user_id = $request->user()->id;
        if($club->save())
        {
            return redirect()->route('club.show', [$club]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    public function destroy($id)
    {
        //
    }
}
