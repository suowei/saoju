<?php namespace App\Http\Controllers;

use App\Club;
use App\Drama;
use App\Sc;
use Illuminate\Http\Request;

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
            return view('search.tag');
    }

}
