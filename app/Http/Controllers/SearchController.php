<?php namespace App\Http\Controllers;

use App\Drama;

use Illuminate\Http\Request;

class SearchController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->input('search') == '')
            return redirect()->route('drama.index');

        $dramas = Drama::where('title', 'LIKE', '%'.$request->input('search').'%')
            ->orWhere('alias', 'LIKE', '%'.$request->input('search').'%')->paginate(20);
        return view('search')->with('search', $request->input('search'))->withDramas($dramas);
    }

}
