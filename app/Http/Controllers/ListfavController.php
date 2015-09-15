<?php namespace App\Http\Controllers;

use App\Dramalist;
use App\Http\Requests;

use App\Listfav;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListfavController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'list' => 'required',
        ]);
        if(Dramalist::find($request->input('list'), ['user_id'])->user_id == $request->user()->id)
            return '抱歉，不能收藏自己创建的剧单> <';

        $favorite = new Listfav;
        $favorite->list_id = $request->input('list');
        $favorite->user_id = $request->user()->id;
        $favorite->created_at = new Carbon;
        if($favorite->save())
        {
            Dramalist::where('id', $favorite->list_id)->increment('favorites');
        }
        return redirect()->route('list.show', [$favorite->list_id]);
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'list' => 'required',
        ]);
        $result = DB::table('listfavs')->where('user_id', $request->user()->id)
            ->where('list_id', $request->input('list'))->delete();
        if($result)
        {
            Dramalist::where('id', $request->input('list'))->decrement('favorites');
        }
        return redirect()->back();
    }
}
