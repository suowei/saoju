<?php

namespace App\Http\Controllers;

use App\Dramalist;
use App\Item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        if($request->has('sort'))
        {
            $sort = $request->input('sort');
        }
        else
        {
            $sort = 'id';
        }
        $lists = Dramalist::with(['user' => function($query)
        {
            $query->select('id','name');
        }])
            ->select('id', 'user_id', 'title', 'introduction', 'created_at', 'updated_at')
            ->orderBy($sort, 'desc')->paginate(50);
        return view('list.index', ['lists' => $lists, 'sort' => $sort]);
    }

    public function create()
    {
        return view('list.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);

        $list = new Dramalist;
        $list->user_id = $request->user()->id;
        $list->title = $request->input('title');
        $list->introduction = $request->input('introduction');
        if($list->save())
        {
            return redirect()->route('list.show', [$list]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show($id)
    {
        $list = Dramalist::find($id);
        $list->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $items = Item::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'genre', 'original', 'count', 'state', 'sc', 'poster_url');
        }, 'episode' => function($query)
        {
            $query->select('id', 'title', 'alias', 'release_date', 'duration', 'poster_url');
        }])
            ->select('id', 'no', 'drama_id', 'episode_id', 'review', 'created_at', 'updated_at')
            ->where('list_id', $id)->orderBy('no')->paginate(20);
        return view('list.show', ['list' => $list, 'items' => $items]);
    }

    public function edit(Request $request, $id)
    {
        $list = Dramalist::find($id, ['id', 'user_id', 'title', 'introduction']);
        if($list->user_id == $request->user()->id)
        {
            return view('list.edit', ['list' => $list]);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);

        $list = Dramalist::find($id);
        $list->title = $request->input('title');
        $list->introduction = $request->input('introduction');

        if($list->save())
        {
            return redirect()->route('list.show', [$list]);
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $list = Dramalist::find($id);
        if ($list->user_id == $request->user()->id)
        {
            $list->delete();
        }
        return redirect()->route('user.lists', [$request->user()->id]);
    }
}
