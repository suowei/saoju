<?php

namespace App\Http\Controllers;

use App\Ft;
use App\Ftepfav;
use App\Ftfav;
use App\Ftrev;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FtfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index()
    {
        $favorites = Ftfav::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'ft' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('user_id', 'ft_id', 'created_at')
            ->orderBy('created_at', 'desc')->take(50)->get();
        $epfavs = Ftepfav::join('fteps', function($join)
        {
            $join->on('fteps.id', '=', 'ftepfavs.ftep_id');
        })
            ->join('fts', function($join) {
                $join->on('fts.id', '=', 'fteps.ft_id');
            })
            ->select('user_id', 'ft_id', 'fts.title as ft_title', 'ftep_id',
                'fteps.title as ftep_title', 'ftepfavs.created_at as created_at')
            ->orderBy('ftepfavs.created_at', 'desc')->take(50)->get();
        $epfavs->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        return view('ftfav.index', ['favorites' => $favorites, 'epfavs' => $epfavs]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'ft' => 'required',
        ]);
        $ft = Ft::find($request->input('ft'), ['id', 'title']);
        return view('ftfav.create', ['ft' => $ft]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ft' => 'required',
        ]);

        $favorite = new Ftfav;
        $favorite->ft_id = $request->input('ft');
        $favorite->user_id = $request->user()->id;
        $favorite->created_at = new Carbon;
        if($favorite->save())
        {
            DB::table('fts')->where('id', $favorite->ft_id)->increment('favorites');
        }
        return redirect()->back();
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'ft_id' => 'required',
            'content' => 'required_with:title',
            'title' => 'max:255',
        ]);

        $favorite = new Ftfav;
        $favorite->user_id = $request->user()->id;
        $favorite->ft_id = $request->input('ft_id');
        $favorite->created_at = new Carbon;
        if($request->has('content'))
        {
            $review = new Ftrev;
            $review->user_id = $favorite->user_id;
            $review->ft_id = $request->input('ft_id');
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('fts')->where('id', $review->ft_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加失败');
            }
        }
        if($favorite->save())
        {
            DB::table('fts')->where('id', $favorite->ft_id)->increment('favorites');
            return redirect()->route('ft.show', [$favorite->ft_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function destroy(Request $request, $ft_id)
    {
        $result = DB::table('ftfavs')->where('user_id', $request->user()->id)->where('ft_id', $ft_id)->delete();
        if($result)
        {
            DB::table('fts')->where('id', $ft_id)->decrement('favorites');
        }
        return redirect()->back();
    }
}
