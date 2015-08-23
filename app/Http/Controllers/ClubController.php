<?php

namespace App\Http\Controllers;

use App\Club;
use App\Clubver;
use App\Sc;
use App\Screv;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        //传递给视图的url参数
        $params = $request->except('page');
        //排序
        if($request->has('sort'))
        {
            $params['sort'] = $request->input('sort');
        }
        else
        {
            $params['sort'] = 'id';
        }
        if($request->has('order'))
        {
            $params['order'] = $request->input('order');
        }
        else
        {
            $params['order'] = 'desc';
        }
        $clubs = Club::select('id', 'name')
            ->orderBy($params['sort'], $params['order'])->paginate(250);
        return view('club.index', ['params' => $params, 'clubs' => $clubs]);
    }

    public function create()
    {
        return view('club.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:clubs',
        ]);

        $club = new Club;
        $club->name = $request->input('name');
        $club->information = $request->input('information');
        if($club->save())
        {
            Clubver::create(['club_id' => $club->id, 'user_id' => $request->user()->id, 'first' => 1,
                'name' => $club->name, 'information' => $club->information]);
            return redirect()->route('club.show', [$club]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $club = Club::find($id, ['id', 'name', 'information', 'reviews']);
        $scs = Sc::select('id', 'name')->where('club_id', $id)->get();
        $reviews = Screv::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('model_id', $id)->where('model', 1)->orderBy('id', 'desc')->take(20)->get();
        if(Auth::check())
        {
            $userReviews = Screv::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $request->user()->id)->where('model_id', $id)->where('model', 1)->get();
        }
        else
        {
            $userReviews = 0;
        }
        return view('club.show', ['club' => $club, 'scs' => $scs, 'reviews' => $reviews, 'userReviews' => $userReviews]);
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
        if($club->save())
        {
            $user_id = $request->user()->id;
            $version = Clubver::where('club_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Clubver;
                $version->club_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->name = $club->name;
            $version->information = $club->information;
            $version->save();

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

    public function search(Request $request)
    {
        $clubs = Club::select('name')->where('name', 'LIKE', '%'.$request->input('q').'%')->get();
        return $clubs;
    }

    public function versions($id)
    {
        $club = Club::find($id, ['id', 'name']);
        $versions = Clubver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'name', 'information', 'created_at', 'updated_at')
            ->where('club_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('club.versions', ['club' => $club, 'versions' => $versions]);
    }
}
