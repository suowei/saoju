<?php namespace App\Http\Controllers;

use App\Club;
use App\Drama;
use App\Sc;
use Illuminate\Http\Request;

class SearchController extends Controller {

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $type = $request->input('type');
        if($type == 0)
        {
            if($keyword == '')
                return redirect()->route('drama.index');
            $dramas = Drama::where('title', 'LIKE', '%'.$keyword.'%')->orWhere('alias', 'LIKE', '%'.$keyword.'%')->paginate(20);
            return view('search.drama', ['keyword' => $keyword, 'dramas' => $dramas]);
        }
        else if($type == 1)
        {
            if($keyword == '')
                return redirect()->route('sc.index');
            $scs = Sc::where('name', 'LIKE', '%'.$keyword.'%')->orWhere('alias', 'LIKE', '%'.$keyword.'%')->paginate(50);
            return view('search.sc', ['keyword' => $keyword, 'scs' => $scs]);
        }
        else if($type == 2)
        {
            if($keyword == '')
                return redirect()->route('club.index');
            $clubs = Club::where('name', 'LIKE', '%'.$keyword.'%')->paginate(50);
            return view('search.club', ['keyword' => $keyword, 'clubs' => $clubs]);
        }
        else
        {
            return 'Input Error!';
        }
    }

}
