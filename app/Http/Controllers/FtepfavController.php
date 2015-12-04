<?php

namespace App\Http\Controllers;

use App\Ft;
use App\Ftep;
use App\Ftepfav;
use App\Ftrev;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FtepfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'ftep' => 'required',
        ]);
        $ftep = Ftep::find($request->input('ftep'), ['id', 'ft_id', 'title']);
        $ft = Ft::find($ftep->ft_id, ['title']);
        return view('ftepfav.create', ['ftep' => $ftep, 'ft' => $ft]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ftep' => 'required',
        ]);

        $favorite = new Ftepfav;
        $favorite->ftep_id = $request->input('ftep');
        $favorite->user_id = $request->user()->id;
        $favorite->created_at = new Carbon;
        if($favorite->save())
        {
            DB::table('fteps')->where('id', $favorite->ftep_id)->increment('favorites');
        }
        return redirect()->back();
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'ft_id' => 'required',
            'ftep_id' => 'required',
            'content' => 'required_with:title',
            'title' => 'max:255',
        ]);

        $favorite = new Ftepfav;
        $favorite->user_id = $request->user()->id;
        $favorite->ftep_id = $request->input('ftep_id');
        $favorite->created_at = new Carbon;
        if($request->has('content'))
        {
            $review = new Ftrev;
            $review->user_id = $favorite->user_id;
            $review->ft_id = $request->input('ft_id');
            $review->ftep_id = $request->input('ftep_id');
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('fts')->where('id', $review->ft_id)->increment('reviews');
                DB::table('fteps')->where('id', $review->ftep_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加失败');
            }
        }
        if($favorite->save())
        {
            DB::table('fteps')->where('id', $favorite->ftep_id)->increment('favorites');
            return redirect()->route('ftep.show', [$favorite->ftep_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function destroy(Request $request, $ftep_id)
    {
        $result = DB::table('ftepfavs')->where('user_id', $request->user()->id)->where('ftep_id', $ftep_id)->delete();
        if($result)
        {
            DB::table('fteps')->where('id', $ftep_id)->decrement('favorites');
        }
        return redirect()->back();
    }
}
