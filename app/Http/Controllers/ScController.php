<?php

namespace App\Http\Controllers;

use App\Sc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ScController extends Controller
{
    public function index()
    {
        $scs = Sc::with(['club' => function($query)
        {
            $query->select('id', 'name');
        }])->select('id', 'name', 'club_id', 'jobs')->get();
        return view('sc.index', ['scs' => $scs]);
    }

    public function create()
    {
        return view('sc.create');
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        //
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
