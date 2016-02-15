<?php

namespace App\Http\Controllers;

use App\Drama;
use App\Epfav;
use App\Episode;
use App\Review;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class EpfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'episode' => 'required',
        ]);
        $episode = Episode::find($request->input('episode'), ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        return view('epfav.create', ['episode' => $episode, 'drama' => $drama]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'episode_id' => 'required',
        ]);

        $epfav = new Epfav;
        $epfav->user_id = $request->user()->id;
        $epfav->episode_id = $request->input('episode_id');
        $epfav->type = $request->input('type');
        if($epfav->type == 0)//想听状态下不能评分
        {
            $epfav->rating = 0;
        }
        else
        {
            $epfav->rating = $request->input('rating');
        }
        if($epfav->save())
        {
            DB::table('users')->where('id', $epfav->user_id)->increment('epfav'.$epfav->type);
            DB::table('episodes')->where('id', $epfav->episode_id)->increment('favorites');
        }
        return redirect()->back();
    }

    //添加收藏及评论
    public function store2(Request $request)
    {
        $this->validate($request, [
            'drama_id' => 'required',
            'episode_id' => 'required',
            'content' => 'required_with:title',
            'visible' => 'required_with:content',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $epfav = new Epfav;
        $epfav->user_id = $request->user()->id;
        $epfav->episode_id = $request->input('episode_id');
        $epfav->type = $request->input('type');
        if($epfav->type == 0)//想听状态下不能评分
        {
            $epfav->rating = 0;
        }
        else
        {
            $epfav->rating = $request->input('rating');
        }
        if($epfav->save())
        {
            DB::table('users')->where('id', $epfav->user_id)->increment('epfav'.$epfav->type);
            DB::table('episodes')->where('id', $epfav->episode_id)->increment('favorites');
            //评论内容不为空则新建评论
            if($request->has('content'))
            {
                $review = new Review;
                $review->user_id = $epfav->user_id;
                $review->drama_id = $request->input('drama_id');
                $review->episode_id = $epfav->episode_id;
                $review->title = $request->input('title');
                $review->content = $request->input('content');
                $review->visible = $request->input('visible');
                if($review->save())
                {
                    DB::table('users')->where('id', $review->user_id)->increment('reviews');
                    DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                    DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
                }
                else
                {
                    return redirect()->back()->withInput()->withErrors('收藏添加成功，评论添加失败');
                }
            }
            return redirect()->route('episode.show', [$epfav->episode_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }

    public function edit(Request $request, $episode_id)
    {
        $episode = Episode::find($episode_id, ['id', 'drama_id', 'title']);
        $drama = Drama::find($episode->drama_id, ['title']);
        $user_id = $request->user()->id;
        $favorite = Epfav::select('type', 'rating')->where('user_id', $user_id)->where('episode_id', $episode_id)->first();
        $review = Review::select('title', 'content', 'visible')->where('user_id', $user_id)->where('episode_id', $episode_id)->first();
        return view('epfav.edit', ['episode' => $episode, 'drama' => $drama, 'favorite' => $favorite, 'review' => $review]);
    }

    public function update(Request $request, $episode_id)
    {
        $this->validate($request, [
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        $favorite = Epfav::where('user_id', $request->user()->id)->where('episode_id', $episode_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $result = DB::table('epfavs')->where('user_id', $favorite->user_id)->where('episode_id', $episode_id)
            ->update(['type' => $favorite->type, 'rating' => $favorite->rating, 'updated_at' => date("Y-m-d H:i:s")]);
        if($result)
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('epfav'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('epfav'.$favorite->type);
        }
        return redirect()->back();
    }

    public function update2(Request $request, $episode_id)
    {
        $this->validate($request, [
            'content' => 'required_with:title',
            'visible' => 'required_with:content',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $favorite = Epfav::where('user_id', $request->user()->id)->where('episode_id', $episode_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        //修改收藏
        $result = DB::table('epfavs')->where('user_id', $favorite->user_id)->where('episode_id', $episode_id)
            ->update(['type' => $favorite->type, 'rating' => $favorite->rating, 'updated_at' => date("Y-m-d H:i:s")]);
        if($result)
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('epfav'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('epfav'.$favorite->type);
            $review = Review::where('user_id', $favorite->user_id)->where('episode_id', $episode_id)->first();
            if($review)//若已有评论则修改
            {
                $review->title = $request->input('title');
                $review->content = $request->input('content');
                $review->visible = $request->input('visible');
                if(!$review->save())
                {
                    return redirect()->back()->withInput()->withErrors('收藏修改成功，评论修改失败');
                }
            }
            else if($request->has('content'))//若原先无评论且此次评论内容不为空则新建评论
            {
                $review = new Review;
                $review->user_id = $favorite->user_id;
                $review->drama_id = $request->input('drama_id');
                $review->episode_id = $favorite->episode_id;
                $review->title = $request->input('title');
                $review->content = $request->input('content');
                $review->visible = $request->input('visible');
                if($review->save())
                {
                    DB::table('users')->where('id', $review->user_id)->increment('reviews');
                    DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                    DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
                }
                else
                {
                    return redirect()->back()->withInput()->withErrors('收藏修改成功，评论添加失败');
                }
            }
            return redirect()->route('episode.show', [$episode_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    public function destroy(Request $request, $episode_id)
    {
        $user_id = $request->user()->id;
        $favorite = Epfav::select('type')->where('user_id', $user_id)->where('episode_id', $episode_id)->first();
        $result = DB::table('epfavs')->where('user_id', $user_id)->where('episode_id', $episode_id)->delete();
        if($result)
        {
            DB::table('users')->where('id', $user_id)->decrement('epfav'.$favorite->type);
            DB::table('episodes')->where('id', $episode_id)->decrement('favorites');
        }
        return redirect()->back();
    }
}
