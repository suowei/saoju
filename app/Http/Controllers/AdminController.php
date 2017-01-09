<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Review;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    public function index()
    {
        $newEpisodes = Episode::join('dramas', function($join)
        {
            $join->on('episodes.drama_id', '=', 'dramas.id');
        })
            ->select('dramas.title as drama_title', 'dramas.type as type', 'dramas.original as original',
                'dramas.author as author', 'dramas.era as era', 'dramas.genre as genre', 'dramas.sc as cv', 'episodes.id as id',
                'episodes.title as episode_title', 'episodes.alias as alias', 'episodes.sc as sc', 'episodes.url as url')
            ->where('release_date', date("Y-m-d", strtotime("-1 day")))
            ->orderBy('type')
            ->get();
        $newEpisodesCount = count($newEpisodes);
        $newEpisodes = $newEpisodes->groupBy('type');
        return view('admin', ['newEpisodes' => $newEpisodes, 'newEpisodesCount' => $newEpisodesCount]);
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
