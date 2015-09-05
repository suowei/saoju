<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Dramalist;
use App\Episode;
use App\Item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
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
        $lists = Dramalist::select('id', 'title')->where('user_id', $request->user()->id)->get();
        if($request->has('drama'))
        {
            $drama = Drama::find($request->input('drama'), ['id', 'title']);
            return view('item.create', ['drama' => $drama, 'lists' => $lists]);
        }
        else if($request->has('episode'))
        {
            $episode = Episode::find($request->input('episode'), ['id', 'drama_id', 'title']);
            $drama = Drama::find($episode->drama_id, ['id', 'title']);
            return view('item.create', ['drama' => $drama, 'episode' => $episode, 'lists' => $lists]);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'list_id' => 'required',
            'drama_id' => 'required',
            'episode_id' => 'required',
        ]);
        if(Item::select('id')->where('list_id', $request->input('list_id'))
            ->where('drama_id', $request->input('drama_id'))
            ->where('episode_id', $request->input('episode_id'))->first())
            return redirect()->back()->withInput()->withErrors('已加入该剧单，请勿重复添加^ ^');

        $item = new Item;
        $item->list_id = $request->input('list_id');
        $item->no = Item::where('list_id', $item->list_id)->max('no') + 1;
        $item->drama_id = $request->input('drama_id');
        $item->episode_id = $request->input('episode_id');
        $item->review = $request->input('review');
        if($item->save())
        {
            Dramalist::where('id', $item->list_id)->update(['updated_at' => $item->created_at]);
            return redirect()->route('list.show', [$item->list_id]);
        }
        else
            return redirect()->back()->withInput()->withErrors('添加失败');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'no' => 'required',
        ]);

        $item = Item::find($id);
        $oldno = $item->no;
        $item->no = $request->input('no');
        $item->review = $request->input('review');
        if($item->save())
        {
            if($oldno < $item->no)
                Item::where('list_id', $item->list_id)->where('id', '!=', $item->id)
                    ->whereBetween('no', [$oldno, $item->no])->decrement('no');
            else if($oldno > $item->no)
                Item::where('list_id', $item->list_id)->where('id', '!=', $item->id)
                    ->whereBetween('no', [$item->no, $oldno])->increment('no');
            Dramalist::where('id', $item->list_id)->update(['updated_at' => $item->updated_at]);
        }
        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        $item = Item::find($id);
        $list = Dramalist::find($item->list_id, ['user_id']);
        if ($list->user_id == $request->user()->id)
        {
            if($item->delete())
            {
                Item::where('list_id', $item->list_id)->where('no', '>', $item->no)->decrement('no');
                Dramalist::where('id', $item->list_id)->update(['updated_at' => $item->deleted_at]);
            }
        }
        return redirect()->back();
    }
}
