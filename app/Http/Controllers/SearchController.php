<?php namespace App\Http\Controllers;

use App\Club;
use App\Drama;
use App\Sc;
use App\Tagmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller {

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        if($keyword == '')
            return redirect()->route('drama.index');
        $dramas = Drama::where('title', 'LIKE', '%'.$keyword.'%')->orWhere('alias', 'LIKE', '%'.$keyword.'%')->get();
        $scs = Sc::where('name', 'LIKE', '%'.$keyword.'%')->orWhere('alias', 'LIKE', '%'.$keyword.'%')->get();
        $clubs = Club::where('name', 'LIKE', '%'.$keyword.'%')->get();
        return view('search.search', ['keyword' => $keyword, 'dramas' => $dramas, 'scs' => $scs, 'clubs' => $clubs]);
    }

    public function tag(Request $request)
    {
        if($request->has('tag'))
            return redirect()->route('drama.tag', [$request->input('tag')]);
        else
        {
            $tagmaps = Tagmap::with('tag')
                ->select(DB::raw('count(*) as count, tag_id'))
                ->groupBy('tag_id')
                ->orderBy('count', 'desc')
                ->take(100)->get();
            return view('search.tag', ['tagmaps' => $tagmaps]);
        }
    }

}
