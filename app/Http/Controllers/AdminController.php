<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Ed;
use App\Epfav;
use App\Episode;
use App\Episodever;
use App\Favorite;
use App\Item;
use App\Review;
use App\Role;
use App\Tagmap;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin');
    }

    public function deleteReview(Request $request)
    {
        $this->validate($request, [
            'review_id' => 'required|integer',
            'created_at' => 'required|date',
            'reason' => 'required|max:255',
        ]);

        $review = Review::find($request->input('review_id'));
        if ($review->created_at == $request->input('created_at'))
        {
            $review->banned = $request->input('reason');
            if($review->save())
            {
                return redirect('/');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('屏蔽失败');
            }
        }
        return redirect()->back()->withInput()->withErrors('校验失败，请仔细核对评论ID与评论时间');
    }

}
